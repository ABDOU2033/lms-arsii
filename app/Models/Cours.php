<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = ['enseignant_id', 'titre', 'description', 'date_creation', 'statut', 'cle_inscription', 'categorie', 'niveau_scolaire', 'annee_universitaire'];

    protected $casts = [
        'date_creation' => 'datetime',
    ];

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function lecons()
    {
        return $this->hasMany(Lecon::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'inscriptions');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function publier()
    {
        $this->update(['statut' => 'publie']);
    }

    public function archiver()
    {
        $this->update(['statut' => 'archive']);
    }

    public function scopePublie($query)
    {
        return $query->where('statut', 'publie');
    }
}