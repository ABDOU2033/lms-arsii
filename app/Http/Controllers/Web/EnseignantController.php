<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\ChoixReponse;
use App\Models\Reponse;
use App\Models\Progression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnseignantController extends Controller
{
    public function dashboard()
    {
        $enseignant = Auth::user()->enseignant;
        $cours = $enseignant->cours()->count();
        $etudiants = $enseignant->cours()->with('etudiants')->get()->pluck('etudiants')->flatten()->unique()->count();
        $quiz = Quiz::whereHas('cours', function ($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->count();
        
        // Calculer la progression moyenne réelle
        $progressionMoy = Progression::whereHas('etudiant.inscriptions.cours', function ($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->avg('pourcentage') ?? 0;

        return view('enseignant.dashboard', compact('cours', 'etudiants', 'quiz', 'progressionMoy'));
    }

    public function indexCours()
    {
        $enseignant = Auth::user()->enseignant;
        $cours = $enseignant->cours()->paginate(10);
        return view('enseignant.cours.index', compact('cours'));
    }

    public function createCours()
    {
        return view('enseignant.cours.create');
    }

    public function storeCours(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $enseignant = Auth::user()->enseignant;
        $enseignant->creerCours($request->only(['titre', 'description']));

        return redirect()->route('enseignant.cours.index')->with('success', 'Cours créé.');
    }

    public function editCours(Cours $cours)
    {
        $this->authorize('update', $cours);
        return view('enseignant.cours.edit', compact('cours'));
    }

    public function updateCours(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|in:brouillon,publie,archive',
        ]);

        $cours->update($request->only(['titre', 'description', 'statut']));

        return redirect()->route('enseignant.cours.index')->with('success', 'Cours mis à jour.');
    }

    public function destroyCours(Cours $cours)
    {
        $this->authorize('delete', $cours);
        $cours->delete();
        return redirect()->route('enseignant.cours.index')->with('success', 'Cours supprimé.');
    }

    public function showCours(Cours $cours)
    {
        $this->authorize('view', $cours);
        $lecons = $cours->lecons;
        $quizzes = $cours->quizzes;
        $etudiants = $cours->etudiants;
        return view('enseignant.cours.show', compact('cours', 'lecons', 'quizzes', 'etudiants'));
    }

    public function storeLecon(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:video,texte',
            'ordre' => 'required|integer',
        ]);

        $cours->lecons()->create($request->only(['titre', 'contenu', 'type', 'ordre']));

        return redirect()->back()->with('success', 'Leçon ajoutée.');
    }

    public function storeQuiz(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'duree' => 'required|integer',
            'note_max' => 'required|integer',
        ]);

        $quiz = $cours->quizzes()->create($request->only(['titre', 'duree', 'note_max']));

        return redirect()->route('enseignant.quiz.show', $quiz)->with('success', 'Quiz ajouté. Ajoutez des questions maintenant.');
    }

    public function showQuiz(Quiz $quiz)
    {
        $this->authorize('view', $quiz->cours);
        $questions = $quiz->questions;

        $resultats = Reponse::whereHas('question', function ($query) use ($quiz) {
            $query->where('quiz_id', $quiz->id);
        })->with('etudiant.user')->get()->groupBy('etudiant.user.nom');

        return view('enseignant.quiz.show', compact('quiz', 'questions', 'resultats'));
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz->cours);
        $request->validate([
            'enonce' => 'required|string',
            'type' => 'required|in:qcm,vrai_faux,texte_libre',
            'points' => 'required|integer|min:1',
            'correct_vrai_faux' => 'required_if:type,vrai_faux|in:Vrai,Faux',
        ]);

        $currentTotal = $quiz->questions()->sum('points');
        $newTotal = $currentTotal + $request->points;
        if ($newTotal > $quiz->note_max) {
            $dispo = max(0, $quiz->note_max - $currentTotal);
            return redirect()->back()->withInput()->withErrors(['points' => "La somme totale des points ($newTotal) ne doit pas dépasser la note maximale du quiz ({$quiz->note_max}). Vous avez $dispo point(s) disponible(s)."]);
        }

        $question = $quiz->questions()->create($request->only(['enonce', 'type', 'points']));

        if ($request->type === 'qcm') {
            $choixValides = 0;
            $correctFound = false;
            
            foreach ($request->choix ?? [] as $choix) {
                if (isset($choix['contenu']) && trim($choix['contenu']) !== '') {
                    $choixValides++;
                    $isCorrect = !empty($choix['est_correcte']);
                    if ($isCorrect) {
                        $correctFound = true;
                    }
                    ChoixReponse::create([
                        'question_id' => $question->id,
                        'contenu' => trim($choix['contenu']),
                        'est_correcte' => $isCorrect,
                    ]);
                }
            }

            if ($choixValides < 2) {
                $question->delete(); // Supprimer la question si elle n'est pas valide
                return redirect()->back()->withInput()->withErrors(['choix' => 'Un QCM doit contenir au moins 2 choix de réponse valides (non vides).']);
            }

            if (!$correctFound) {
                $question->delete(); // Supprimer la question si elle n'est pas valide
                return redirect()->back()->withInput()->withErrors(['choix' => 'Vous devez cocher au moins une réponse comme correcte pour un QCM.']);
            }
        } elseif ($request->type === 'vrai_faux') {
            $correct = $request->input('correct_vrai_faux', 'Vrai');
            ChoixReponse::create(['question_id' => $question->id, 'contenu' => 'Vrai', 'est_correcte' => $correct === 'Vrai']);
            ChoixReponse::create(['question_id' => $question->id, 'contenu' => 'Faux', 'est_correcte' => $correct === 'Faux']);
        }

        return redirect()->back()->with('success', 'Question ajoutée avec succès.');
    }

    // Leçons CRUD
    public function createLecon(Cours $cours)
    {
        $this->authorize('update', $cours);
        return view('enseignant.lecon.create', compact('cours'));
    }

    public function editLecon(Lecon $lecon)
    {
        $this->authorize('update', $lecon->cours);
        $cours = $lecon->cours;
        return view('enseignant.lecon.edit', compact('lecon', 'cours'));
    }

    public function updateLecon(Request $request, Lecon $lecon)
    {
        $this->authorize('update', $lecon->cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:video,texte',
            'ordre' => 'required|integer',
        ]);

        $lecon->update($request->only(['titre', 'contenu', 'type', 'ordre']));

        return redirect()->route('enseignant.cours.show', $lecon->cours)->with('success', 'Leçon mise à jour.');
    }

    public function destroyLecon(Lecon $lecon)
    {
        $this->authorize('update', $lecon->cours);
        $cours = $lecon->cours;
        $lecon->delete();
        return redirect()->route('enseignant.cours.show', $cours)->with('success', 'Leçon supprimée.');
    }

    // Quiz CRUD
    public function createQuiz(Cours $cours)
    {
        $this->authorize('update', $cours);
        return view('enseignant.quiz.create', compact('cours'));
    }

    public function editQuiz(Quiz $quiz)
    {
        $this->authorize('view', $quiz->cours);
        $cours = $quiz->cours;
        return view('enseignant.quiz.edit', compact('quiz', 'cours'));
    }

    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz->cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'duree' => 'required|integer',
            'note_max' => 'required|integer',
        ]);

        $quiz->update($request->only(['titre', 'duree', 'note_max']));

        // Redistribution logique (algorithme du plus grand reste)
        $questions = $quiz->questions()->get();
        if ($questions->count() > 0) {
            $sumOriginal = $questions->sum('points');
            $nouvelleNoteMax = $quiz->note_max;

            if ($sumOriginal !== $nouvelleNoteMax && $sumOriginal > 0) {
                // Si la note max est inférieure au nombre de questions, forcer au min de 1 par question
                if ($nouvelleNoteMax < $questions->count()) {
                    $nouvelleNoteMax = $questions->count();
                    $quiz->update(['note_max' => $nouvelleNoteMax]);
                }

                $mapped = $questions->map(function ($q) use ($sumOriginal, $nouvelleNoteMax) {
                    $exact = ($q->points / $sumOriginal) * $nouvelleNoteMax;
                    $floor = floor($exact);
                    return [
                        'question' => $q,
                        'exact' => $exact,
                        'floor' => $floor,
                        'remainder' => $exact - $floor
                    ];
                });

                $sumFloor = $mapped->sum('floor');
                $diff = $nouvelleNoteMax - $sumFloor;

                // Trier par ordre décroissant de reste
                $mapped = $mapped->sortByDesc('remainder')->values();

                // Assigner les points entiers et distribuer la différence
                foreach ($mapped as $index => $item) {
                    $finalPoints = $item['floor'] + ($index < $diff ? 1 : 0);
                    if ($finalPoints < 1) $finalPoints = 1;
                    $item['question']->update(['points' => $finalPoints]);
                }
                
                // Ajustement de sécurité au cas où l'arrondi a dépassé
                $finalSum = $quiz->questions()->sum('points');
                if ($finalSum > $nouvelleNoteMax) {
                    $diff = $finalSum - $nouvelleNoteMax;
                    $largest = $quiz->questions()->orderBy('points', 'desc')->get();
                    foreach ($largest as $lq) {
                        if ($diff > 0 && $lq->points > 1) {
                            $reduction = min($diff, $lq->points - 1);
                            $lq->update(['points' => $lq->points - $reduction]);
                            $diff -= $reduction;
                        }
                    }
                }
            }
        }

        return redirect()->route('enseignant.cours.show', $quiz->cours)->with('success', 'Quiz mis à jour.');
    }

    public function destroyQuiz(Quiz $quiz)
    {
        $this->authorize('view', $quiz->cours);
        $cours = $quiz->cours;
        $quiz->delete();
        return redirect()->route('enseignant.cours.show', $cours)->with('success', 'Quiz supprimé.');
    }

    // Questions CRUD
    public function createQuestion(Quiz $quiz)
    {
        $this->authorize('update', $quiz->cours);
        return view('enseignant.question.create', compact('quiz'));
    }

    public function editQuestion(Question $question)
    {
        $this->authorize('update', $question->quiz->cours);
        $quiz = $question->quiz;
        $choixReponses = $question->choixReponses;
        return view('enseignant.question.edit', compact('question', 'quiz', 'choixReponses'));
    }

    public function updateQuestion(Request $request, Question $question)
    {
        $this->authorize('update', $question->quiz->cours);
        $request->validate([
            'enonce' => 'required|string',
            'type' => 'required|in:qcm,vrai_faux,texte_libre',
            'points' => 'required|integer|min:1',
            'correct_vrai_faux' => 'required_if:type,vrai_faux|in:Vrai,Faux',
        ]);

        $currentTotal = $question->quiz->questions()->where('id', '!=', $question->id)->sum('points');
        $newTotal = $currentTotal + $request->points;
        if ($newTotal > $question->quiz->note_max) {
            $dispo = max(0, $question->quiz->note_max - $currentTotal);
            return redirect()->back()->withInput()->withErrors(['points' => "La somme totale des points ($newTotal) ne doit pas dépasser la note maximale du quiz ({$question->quiz->note_max}). Vous pouvez attribuer un maximum de $dispo point(s) à cette question."]);
        }

        $question->update($request->only(['enonce', 'type', 'points']));

        if ($request->type === 'qcm') {
            $question->choixReponses()->delete();
            $correctFound = false;
            foreach ($request->choix ?? [] as $choix) {
                if (isset($choix['contenu']) && trim($choix['contenu']) !== '') {
                    $isCorrect = !empty($choix['est_correcte']);
                    if ($isCorrect) {
                        $correctFound = true;
                    }
                    ChoixReponse::create([
                        'question_id' => $question->id,
                        'contenu' => trim($choix['contenu']),
                        'est_correcte' => $isCorrect,
                    ]);
                }
            }
            if (!$correctFound) {
                return redirect()->back()->withInput()->withErrors(['choix' => 'Un QCM doit contenir au moins une réponse correcte.']);
            }
        } elseif ($request->type === 'vrai_faux') {
            $question->choixReponses()->delete();
            $correct = $request->input('correct_vrai_faux', 'Vrai');
            ChoixReponse::create(['question_id' => $question->id, 'contenu' => 'Vrai', 'est_correcte' => $correct === 'Vrai']);
            ChoixReponse::create(['question_id' => $question->id, 'contenu' => 'Faux', 'est_correcte' => $correct === 'Faux']);
        }

        return redirect()->route('enseignant.quiz.show', $question->quiz)->with('success', 'Question mise à jour.');
    }

    public function destroyQuestion(Question $question)
    {
        $this->authorize('update', $question->quiz->cours);
        $quiz = $question->quiz;
        $question->delete();

        return redirect()->route('enseignant.quiz.show', $quiz)->with('success', 'Question supprimée.');
    }

    public function resultats()
    {
        $enseignant = Auth::user()->enseignant;

        $quizzes = Quiz::whereHas('cours', function ($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->with('cours')->get();

        $resultats = Reponse::whereHas('question.quiz.cours', function ($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->with('etudiant.user', 'question.quiz')->get()->groupBy('question.quiz.titre');

        $progressions = Progression::whereHas('cours', function ($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->with('etudiant.user', 'cours')->orderBy('pourcentage', 'desc')->get();

        return view('enseignant.resultats', compact('quizzes', 'resultats', 'progressions'));
    }
}