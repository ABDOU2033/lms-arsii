<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'niveau'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'inscriptions');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }

    public function leconsVues()
    {
        return $this->hasMany(LeconVue::class);
    }

    public function consulterCours()
    {
        return $this->cours()->where('statut', 'publie')->get();
    }

    public function voirProgression(Cours $cours)
    {
        return $this->progressions()->where('cours_id', $cours->id)->first();
    }
}