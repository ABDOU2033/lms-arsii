<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['cours_id', 'titre', 'duree', 'note_max'];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function totalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function scoreScaleFactor(): float
    {
        $totalPoints = $this->totalPoints();
        return $totalPoints > 0 ? $this->note_max / $totalPoints : 0;
    }

    public function calculerScore(Etudiant $etudiant)
    {
        $reponses = Reponse::where('etudiant_id', $etudiant->id)
            ->whereHas('question', function ($query) {
                $query->where('quiz_id', $this->id);
            })->get();

        $score = $reponses->sum('score_obtenu');
        $scaledScore = $score * $this->scoreScaleFactor();

        return round(min($scaledScore, $this->note_max), 2);
    }

    public function normalizedQuestionPoints(Question $question): float
    {
        return round($question->points * $this->scoreScaleFactor(), 2);
    }

    public function corrigerAutomatiquement(Reponse $reponse)
    {
        if ($reponse->question->type === 'qcm' || $reponse->question->type === 'vrai_faux') {
            $correct = ChoixReponse::where('question_id', $reponse->question_id)
                ->where('est_correcte', true)
                ->where('contenu', $reponse->contenu)
                ->exists();
            $reponse->update(['est_correcte' => $correct, 'score_obtenu' => $correct ? $reponse->question->points : 0]);
        }
        // For texte_libre, manual correction needed
    }
}