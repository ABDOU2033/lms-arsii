<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Accès refusé.');
        }

        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Accès refusé.');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Accès refusé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student'
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur créé avec succès!');
    }

    public function edit(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, 'Accès refusé.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, 'Accès refusé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,teacher,student'
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur mis à jour!');
    }

    public function destroy(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            abort(403, 'Accès refusé.');
        }

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre compte!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur supprimé!');
    }
}
