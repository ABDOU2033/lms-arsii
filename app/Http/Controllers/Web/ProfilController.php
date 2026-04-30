<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profil.show', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'mot_de_passe' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $data = $request->only(['nom', 'email']);
        if ($request->mot_de_passe) {
            $data['mot_de_passe'] = Hash::make($request->mot_de_passe);
        }
        $user->update($data);

        return redirect()->back()->with('success', 'Profil mis à jour.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'mot_de_passe' => 'required|string',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
            return back()->withErrors(['mot_de_passe' => 'Mot de passe incorrect']);
        }

        Auth::logout();
        $user->delete();

        return redirect('/login')->with('success', 'Compte supprimé avec succès.');
    }
}