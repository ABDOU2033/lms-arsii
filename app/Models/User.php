<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'email', 'mot_de_passe', 'role', 'actif', 'statut'
    ];

    protected $hidden = ['mot_de_passe', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'actif' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }

    public function coursEnseignes()
    {
        return $this->hasMany(Cours::class, 'enseignant_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'etudiant_id');
    }

    public function coursInscrits()
    {
        return $this->belongsToMany(Cours::class, 'inscriptions', 'etudiant_id', 'cours_id');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'etudiant_id');
    }

    public function progressions()
    {
        return $this->hasMany(Progression::class, 'etudiant_id');
    }

    public function leconsVues()
    {
        return $this->hasMany(LeconVue::class, 'etudiant_id');
    }

    public function isEtudiant()
    {
        return $this->role === 'etudiant';
    }

    public function isEnseignant()
    {
        return $this->role === 'enseignant';
    }

    public function isAdministrateur()
    {
        return $this->role === 'administrateur';
    }
}