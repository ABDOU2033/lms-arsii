<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = ['etudiant_id', 'cours_id', 'pourcentage', 'date_maj'];

    protected $casts = [
        'date_maj' => 'datetime',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function mettreAJour()
    {
        $totalLecons = $this->cours->lecons()->count();
        $leconsVues = LeconVue::where('etudiant_id', $this->etudiant_id)
            ->where('cours_id', $this->cours_id)
            ->count();

        $totalQuizzes = $this->cours->quizzes()->count();
        $quizzesCompletes = 0;

        foreach ($this->cours->quizzes as $quiz) {
            $reponses = Reponse::where('etudiant_id', $this->etudiant_id)
                ->whereHas('question', function ($query) use ($quiz) {
                    $query->where('quiz_id', $quiz->id);
                })->get();

            $reponsesCount = $reponses->count();
            $hasPending = $reponses->contains('score_obtenu', -1);

            if ($reponsesCount > 0 && $reponsesCount >= $quiz->questions()->count() && !$hasPending) {
                $quizzesCompletes++;
            }
        }

        $lessonProgress = $totalLecons > 0 ? ($leconsVues / $totalLecons) * 100 : 0;
        $quizProgress = $totalQuizzes > 0 ? ($quizzesCompletes / $totalQuizzes) * 100 : 0;

        if ($totalLecons > 0 && $totalQuizzes > 0) {
            $pourcentage = round(($lessonProgress * 0.7) + ($quizProgress * 0.3), 1);
        } elseif ($totalLecons > 0) {
            $pourcentage = round($lessonProgress, 1);
        } elseif ($totalQuizzes > 0) {
            $pourcentage = round($quizProgress, 1);
        } else {
            $pourcentage = 0;
        }

        $this->update(['pourcentage' => $pourcentage, 'date_maj' => now()]);
    }
}