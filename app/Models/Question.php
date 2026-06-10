<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'enonce', 'type', 'points', 'reponse_attendue'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choixReponses()
    {
        return $this->hasMany(ChoixReponse::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }
}