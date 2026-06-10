<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    public function calculerScore(Etudiant $etudiant)
    {
        $reponses = Reponse::where('etudiant_id', $etudiant->id)
            ->whereHas('question', function ($query) {
                $query->where('quiz_id', $this->id);
            })->get();

        // Score = somme directe des points obtenus (pas de coefficient)
        $score = $reponses->sum(function($reponse) {
            return max(0, $reponse->score_obtenu);
        });

        // Retourner le score brut (sera affiché sur note_max)
        return round(min($score, $this->note_max), 2);
    }

    public function corrigerAutomatiquement(Reponse $reponse)
    {
        // S'assurer que la question est chargée avec ses attributs
        $reponse->load('question');
        
        \Log::info('Auto-correction question ID: ' . $reponse->question_id . ', Type: ' . $reponse->question->type . ', Réponse attendue: ' . $reponse->question->reponse_attendue);
        
        if ($reponse->question->type === 'vrai_faux') {
            // Vrai/Faux : comparaison simple
            $correct = ChoixReponse::where('question_id', $reponse->question_id)
                ->where('est_correcte', true)
                ->where('contenu', $reponse->contenu)
                ->exists();
            $reponse->update([
                'est_correcte' => $correct,
                'score_obtenu' => $correct ? $reponse->question->points : 0,
            ]);

        } elseif ($reponse->question->type === 'qcm') {
            // QCM : nouvelle formule de score
            // - Toutes les réponses cochées sont correctes → score complet
            // - Mélange correct + incorrect → demi-score (50%)
            // - Uniquement des incorrectes → 0
            // - Maximum 2 réponses autorisées (contrôle côté vue)
            $selectedAnswers = array_filter(array_map('trim', explode('|', $reponse->contenu)));

            // Toutes les bonnes réponses pour cette question
            $correctChoices = ChoixReponse::where('question_id', $reponse->question_id)
                ->where('est_correcte', true)
                ->pluck('contenu')
                ->map('trim')
                ->toArray();

            // Toutes les mauvaises réponses
            $wrongChoices = ChoixReponse::where('question_id', $reponse->question_id)
                ->where('est_correcte', false)
                ->pluck('contenu')
                ->map('trim')
                ->toArray();

            $totalCorrect = count($correctChoices);
            $points = $reponse->question->points;

            if ($totalCorrect > 0) {
                // Compter les bonnes réponses cochées
                $bonnesCochees = count(array_intersect($selectedAnswers, $correctChoices));

                // Compter les mauvaises réponses cochées
                $mauvaisesCochees = count(array_intersect($selectedAnswers, $wrongChoices));

                // Nouvelle formule :
                if ($bonnesCochees > 0 && $mauvaisesCochees === 0) {
                    // Uniquement des bonnes réponses → score complet
                    $score = round($points * ($bonnesCochees / $totalCorrect), 2);
                } elseif ($bonnesCochees > 0 && $mauvaisesCochees > 0) {
                    // Mélange correct + incorrect → demi-score (50%)
                    $score = round($points / 2, 2);
                } else {
                    // Uniquement des mauvaises réponses → 0
                    $score = 0;
                }

                $estCorrecte = ($bonnesCochees === $totalCorrect && $mauvaisesCochees === 0);

                $reponse->update([
                    'est_correcte' => $estCorrecte,
                    'score_obtenu' => $score,
                ]);
            } else {
                $reponse->update([
                    'est_correcte' => false,
                    'score_obtenu' => 0,
                ]);
            }
        } elseif ($reponse->question->type === 'texte_libre') {
            // Texte libre : correction automatique si réponse attendue fournie
            $reponseAttendue = $reponse->question->reponse_attendue;
            
            \Log::info('Texte libre - Réponse étudiant: ' . $reponse->contenu . ', Attendue: ' . $reponseAttendue);
            
            if (!empty($reponseAttendue)) {
                // Normaliser les textes pour comparaison
                $studentAnswer = $this->normalizeText($reponse->contenu);
                $expectedAnswer = $this->normalizeText($reponseAttendue);
                
                \Log::info('Normalisé - Étudiant: ' . $studentAnswer . ', Attendu: ' . $expectedAnswer);
                
                // Vérifier correspondance exacte (après normalisation)
                if ($studentAnswer === $expectedAnswer) {
                    \Log::info('Correspondance exacte détectée');
                    $reponse->update([
                        'est_correcte' => true,
                        'score_obtenu' => $reponse->question->points,
                    ]);
                } else {
                    // Calculer similarité avec similar_text
                    $similarity = 0;
                    similar_text($studentAnswer, $expectedAnswer, $similarity);
                    
                    \Log::info('Similarité: ' . $similarity . '%');
                    
                    // Si similarité >= 60%, donner la moitié du score
                    if ($similarity >= 60) {
                        $demiScore = round($reponse->question->points / 2, 2);
                        $reponse->update([
                            'est_correcte' => false,
                            'score_obtenu' => $demiScore,
                        ]);
                        \Log::info('Réponse proche, demi-score attribué: ' . $demiScore . ' pts');
                    } else {
                        // Trop différent, score = 0
                        $reponse->update([
                            'est_correcte' => false,
                            'score_obtenu' => 0,
                        ]);
                        \Log::info('Trop différent, score = 0');
                    }
                }
            } else {
                \Log::info('Pas de réponse attendue, correction manuelle requise');
            }
            // Si pas de réponse attendue, laisser score_obtenu = -1 pour correction manuelle
        }
        
        \Log::info('Après correction - Score: ' . $reponse->score_obtenu . ', Correct: ' . ($reponse->est_correcte ? 'true' : 'false'));
    }
    
    /**
     * Normaliser le texte pour comparaison (minuscule, sans espaces multiples, sans accents)
     */
    private function normalizeText($text)
    {
        // Convertir en minuscules
        $text = mb_strtolower($text, 'UTF-8');
        
        // Supprimer les accents
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        
        // Supprimer espaces multiples et trim
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Supprimer ponctuation
        $text = preg_replace('/[^\w\s]/u', '', $text);
        
        return $text;
    }
}