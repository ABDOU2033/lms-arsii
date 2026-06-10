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
        $user = Auth::user()->load(['enseignant', 'etudiant']);
        return view('profil.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nom' => 'required|string|max:255',
            // Ajoute la règle prenom uniquement si la colonne existe
            ...(\Illuminate\Support\Facades\Schema::hasColumn('users', 'prenom') ? ['prenom' => 'nullable|string|max:255'] : []),
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'mot_de_passe' => 'nullable|string|min:8|confirmed',
        ];

        // Règles spécifiques au rôle
        if ($user->role === 'enseignant') {
            $rules['specialite'] = 'nullable|string|max:255';
            $rules['bio']        = 'nullable|string|max:2000';
        }
        if ($user->role === 'etudiant') {
            $rules['niveau'] = 'nullable|string|max:100';
        }

        $request->validate($rules);

        // Mise à jour des infos User
        $data = $request->only(['nom', 'email']);
        // Ajouter 'prenom' uniquement si la colonne existe dans la table
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'prenom')) {
            $data['prenom'] = $request->input('prenom');
        }
        if ($request->filled('mot_de_passe')) {
            $data['mot_de_passe'] = Hash::make($request->mot_de_passe);
        }
        $user->update($data);

        // Mise à jour du profil Enseignant
        if ($user->role === 'enseignant' && $user->enseignant) {
            $user->enseignant->update([
                'specialite' => $request->input('specialite'),
                'bio'        => $request->input('bio'),
            ]);
        }

        // Mise à jour du profil Étudiant
        if ($user->role === 'etudiant' && $user->etudiant) {
            $user->etudiant->update([
                'niveau' => $request->input('niveau'),
            ]);
        }

        return redirect()->back()->with('success', 'Profil mis à jour avec succès !');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'mot_de_passe' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
            return back()->withErrors(['mot_de_passe' => 'Mot de passe incorrect.']);
        }

        Auth::logout();
        $user->delete();

        return redirect('/login')->with('success', 'Compte supprimé avec succès.');
    }
}