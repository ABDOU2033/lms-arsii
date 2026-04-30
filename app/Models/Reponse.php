<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;

    protected $fillable = ['etudiant_id', 'question_id', 'contenu', 'est_correcte', 'date_reponse', 'score_obtenu'];

    protected $casts = [
        'est_correcte' => 'boolean',
        'date_reponse' => 'datetime',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}