<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    private function getStats(): array
    {
        return [
            'nb_cours'      => Cours::count(),
            'nb_etudiants'  => User::where('role', 'etudiant')->where('actif', true)->count(),
            'nb_enseignants'=> User::where('role', 'enseignant')->where('actif', true)->count(),
        ];
    }

    public function showLogin()
    {
        return view('auth.login', $this->getStats());
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->mot_de_passe, $user->mot_de_passe) && $user->actif) {
            Auth::login($user);

            // Redirect based on role
            switch ($user->role) {
                case 'etudiant':
                    return redirect('/etudiant/dashboard');
                case 'enseignant':
                    return redirect('/enseignant/dashboard');
                case 'administrateur':
                    return redirect('/admin/dashboard');
            }
        }

        return back()->withErrors(['email' => 'Identifiants invalides ou compte inactif.']);
    }

    public function showRegister()
    {
        return view('auth.register', $this->getStats());
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'role' => 'required|in:etudiant,enseignant',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role' => $request->role,
            'actif' => false, // Activation by admin
        ]);

        if ($request->role === 'etudiant') {
            Etudiant::create(['user_id' => $user->id, 'niveau' => $request->niveau ?? '']);
        } elseif ($request->role === 'enseignant') {
            Enseignant::create(['user_id' => $user->id, 'specialite' => $request->specialite ?? '', 'bio' => $request->bio ?? '']);
        }

        return redirect('/login')->with('success', 'Inscription réussie. Attendez l\'activation par un administrateur.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}