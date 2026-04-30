<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\Reponse;
use App\Models\LeconVue;
use App\Models\Inscription;
use App\Models\Progression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EtudiantController extends Controller
{
    public function dashboard()
    {
        $etudiant = Auth::user()->etudiant;
        $coursInscrits = $etudiant->cours()->count();
        $quizPasses = Reponse::where('etudiant_id', $etudiant->id)->distinct('question_id')->count();
        $progressionMoy = Progression::where('etudiant_id', $etudiant->id)->avg('pourcentage') ?? 0;
        $scoreMoy = Reponse::where('etudiant_id', $etudiant->id)->avg('score_obtenu') ?? 0;

        return view('etudiant.dashboard', compact('coursInscrits', 'quizPasses', 'progressionMoy', 'scoreMoy'));
    }

    public function catalogue()
    {
        $cours = Cours::where('statut', 'publie')->paginate(10);
        return view('etudiant.catalogue', compact('cours'));
    }

    public function sInscrire(Cours $cours)
    {
        $etudiant = Auth::user()->etudiant;
        if (!$etudiant->cours()->where('cours_id', $cours->id)->exists()) {
            Inscription::create(['etudiant_id' => $etudiant->id, 'cours_id' => $cours->id]);
            Progression::create(['etudiant_id' => $etudiant->id, 'cours_id' => $cours->id, 'pourcentage' => 0]);
        }
        return redirect()->back()->with('success', 'Inscrit avec succès.');
    }

    public function mesCours()
    {
        $etudiant = Auth::user()->etudiant;
        $cours = $etudiant->cours()->with('progressions')->get();
        return view('etudiant.cours.index', compact('cours'));
    }

    public function showCours(Cours $cours)
    {
        $etudiant = Auth::user()->etudiant;
        if (!$etudiant->cours()->where('cours_id', $cours->id)->exists()) {
            abort(403, 'Vous n\'êtes pas inscrit à ce cours.');
        }
        $lecons = $cours->lecons()->orderBy('ordre')->get();
        // Force fresh load of quizzes from database
        $quizzes = Quiz::where('cours_id', $cours->id)->with('questions')->get();
        $cours->setRelation('quizzes', $quizzes);
        foreach ($quizzes as $quiz) {
            $quiz->student_score = $quiz->calculerScore($etudiant);
        }

        $leconsVuesIds = LeconVue::where('etudiant_id', $etudiant->id)
            ->where('cours_id', $cours->id)
            ->pluck('lecon_id')
            ->toArray();

        $progression = Progression::where('etudiant_id', $etudiant->id)->where('cours_id', $cours->id)->first();
        if (!$progression) {
            $progression = Progression::create([
                'etudiant_id' => $etudiant->id,
                'cours_id' => $cours->id,
                'pourcentage' => 0
            ]);
        }
        return view('etudiant.cours.show', compact('cours', 'lecons', 'quizzes', 'progression', 'leconsVuesIds'));
    }

    public function showLecon(Lecon $lecon)
    {
        return view('etudiant.lecon.show', compact('lecon'));
    }

    public function completeLecon(Lecon $lecon)
    {
        $etudiant = Auth::user()->etudiant;
        LeconVue::firstOrCreate([
            'etudiant_id' => $etudiant->id,
            'cours_id' => $lecon->cours_id,
            'lecon_id' => $lecon->id
        ]);

        $progression = Progression::where('etudiant_id', $etudiant->id)->where('cours_id', $lecon->cours_id)->first();
        if ($progression) {
            $progression->mettreAJour();
        }

        return redirect()->route('etudiant.lecon.show', $lecon)->with('success', 'Leçon marquée comme terminée.');
    }

    public function showQuiz(Quiz $quiz)
    {
        $etudiant = Auth::user()->etudiant;

        // Recharger le quiz et ses questions pour récupérer les modifications récentes
        $quiz->refresh();
        $quiz->load('questions.choixReponses');

        return view('etudiant.quiz.show', compact('quiz'));
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        try {
            // Recharger le quiz et ses questions pour récupérer les dernières modifications
            $quiz->refresh();
            $quiz->load('questions');

            // Vérifier que le quiz a des questions
            if ($quiz->questions()->count() === 0) {
                return back()->withErrors(['general' => 'Ce quiz n\'a pas encore de questions.']);
            }

            $request->validate([
                'reponses' => 'required|array|min:1',
                'reponses.*' => 'required|string|max:1000',
            ]);

            $etudiant = Auth::user()->etudiant;

            // Vérifier que toutes les questions ont une réponse
            $questionsCount = $quiz->questions()->count();
            if (count($request->reponses) !== $questionsCount) {
                return back()->withErrors(['reponses' => 'Vous devez répondre à toutes les questions.']);
            }

            // Supprimer les anciennes réponses pour permettre une nouvelle tentative
            Reponse::where('etudiant_id', $etudiant->id)
                ->whereHas('question', function ($query) use ($quiz) {
                    $query->where('quiz_id', $quiz->id);
                })->delete();

            foreach ($request->reponses as $questionId => $contenu) {
                // Vérifier que la question appartient bien à ce quiz
                $question = $quiz->questions()->find($questionId);
                if (!$question) {
                    continue; // Skip invalid questions
                }

                // Créer une nouvelle réponse
                $reponse = Reponse::create([
                    'etudiant_id' => $etudiant->id,
                    'question_id' => $questionId,
                    'contenu' => trim($contenu),
                    'est_correcte' => false,
                    'score_obtenu' => 0,
                ]);

                Log::info('Created response: ' . $reponse->id . ' for question ' . $questionId . ' in quiz ' . $quiz->id);

                // Auto correction (met à jour est_correcte et score_obtenu si qcm/vrai_faux)
                $quiz->corrigerAutomatiquement($reponse);
            }

            // Met à jour la progression du cours
            $progression = Progression::firstOrCreate(
                ['etudiant_id' => $etudiant->id, 'cours_id' => $quiz->cours_id],
                ['pourcentage' => 0]
            );
            $progression->mettreAJour();

            // Récupérer les résultats du quiz pour cette tentative
            $questionIds = $quiz->questions()->pluck('id');
            $reponses = Reponse::where('etudiant_id', $etudiant->id)
                ->whereIn('question_id', $questionIds)
                ->with('question.choixReponses')->get();

            Log::info('Quiz submission - Student: ' . $etudiant->id . ', Quiz: ' . $quiz->id . ', Question IDs: ' . $questionIds->join(',') . ', Responses found: ' . $reponses->count());

            $scoreTotal = $quiz->calculerScore($etudiant);
            $questions = $quiz->questions()->with('choixReponses')->get();

            return view('etudiant.quiz.resultat', compact('quiz', 'reponses', 'scoreTotal', 'questions', 'etudiant'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la soumission du quiz: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Une erreur est survenue lors de la soumission du quiz. Veuillez réessayer.']);
        }
    }

    public function resultats()
    {
        $etudiant = Auth::user()->etudiant;
        $resultats = Reponse::where('etudiant_id', $etudiant->id)
            ->with('question.quiz.cours')
            ->orderBy('date_reponse', 'desc')
            ->get()
            ->groupBy('question.quiz.cours.titre');
        return view('etudiant.resultats', compact('resultats'));
    }

    public function debugQuiz(Quiz $quiz)
    {
        return view('etudiant.debug', compact('quiz'));
    }
}