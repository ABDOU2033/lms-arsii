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
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        
        // Calculer la progression moyenne réelle (uniquement les cours de cet enseignant)
        $progressionMoy = Progression::whereHas('cours', function ($query) use ($enseignant) {
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
            'categorie' => 'nullable|string|max:100',
            'niveau_scolaire' => 'nullable|string|max:50',
            'annee_universitaire' => 'nullable|string|max:9',
        ]);

        $enseignant = Auth::user()->enseignant;
        $enseignant->creerCours($request->only(['titre', 'description', 'categorie', 'niveau_scolaire', 'annee_universitaire']));

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
            'categorie' => 'nullable|string|max:100',
            'niveau_scolaire' => 'nullable|string|max:50',
            'annee_universitaire' => 'nullable|string|max:9',
        ]);

        $cours->update($request->only(['titre', 'description', 'statut', 'categorie', 'niveau_scolaire', 'annee_universitaire']));

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

    public function genererCleInscription(Cours $cours)
    {
        $this->authorize('update', $cours);
        $cle = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        $cours->update(['cle_inscription' => $cle]);
        return redirect()->route('enseignant.cours.show', $cours)->with('success', 'Clé d\'inscription générée: ' . $cle);
    }

    public function storeLecon(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required_unless:type,pdf|nullable|string',
            'type' => 'required|in:video,texte,pdf',
            'ordre' => 'required|integer',
            'fichier_pdf' => 'required_if:type,pdf|file|mimes:pdf|max:20480',
        ]);

        $data = $request->only(['titre', 'contenu', 'type', 'ordre']);

        // Upload PDF si type = pdf
        if ($request->type === 'pdf' && $request->hasFile('fichier_pdf')) {
            $path = $request->file('fichier_pdf')->store('lecons/pdf', 'public');
            $data['contenu'] = $path;
        }

        $cours->lecons()->create($data);

        return redirect()->back()->with('success', 'Leçon ajoutée.');
    }

    public function storeQuiz(Request $request, Cours $cours)
    {
        $this->authorize('update', $cours);
        $request->validate([
            'titre' => 'required|string|max:255',
            'duree' => 'required|integer',
        ]);

        // note_max = 0 au départ, sera calculé automatiquement quand des questions seront ajoutées
        $quiz = $cours->quizzes()->create([
            'titre' => $request->titre,
            'duree' => $request->duree,
            'note_max' => 0,
        ]);

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
            'reponse_attendue' => 'nullable|string',
        ]);

        $question = $quiz->questions()->create($request->only(['enonce', 'type', 'points', 'reponse_attendue']));

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

        // Auto-update note_max to the new total sum of question points
        $quiz->update(['note_max' => $quiz->questions()->sum('points')]);

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
            'contenu' => 'required_unless:type,pdf|nullable|string',
            'type' => 'required|in:video,texte,pdf',
            'ordre' => 'required|integer',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $data = $request->only(['titre', 'contenu', 'type', 'ordre']);

        // Upload nouveau PDF si fourni
        if ($request->type === 'pdf' && $request->hasFile('fichier_pdf')) {
            // Supprimer l'ancien PDF
            if ($lecon->type === 'pdf' && $lecon->contenu) {
                Storage::disk('public')->delete($lecon->contenu);
            }
            $path = $request->file('fichier_pdf')->store('lecons/pdf', 'public');
            $data['contenu'] = $path;
        } elseif ($request->type === 'pdf' && !$request->hasFile('fichier_pdf')) {
            // Garder l'ancien PDF si pas de nouveau fichier
            $data['contenu'] = $lecon->contenu;
        }

        $lecon->update($data);

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
        ]);

        $quiz->update([
            'titre' => $request->titre,
            'duree' => $request->duree,
        ]);

        // note_max toujours égale à la somme des points des questions
        $quiz->update(['note_max' => $quiz->questions()->sum('points')]);

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
            'reponse_attendue' => 'nullable|string',
        ]);

        $question->update($request->only(['enonce', 'type', 'points', 'reponse_attendue']));

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
        } elseif ($request->type === 'texte_libre') {
            // Supprimer les anciennes réponses si on passe à texte_libre
            $question->choixReponses()->delete();
        }

        // Auto-update note_max to the new total sum of question points
        $question->quiz->update(['note_max' => $question->quiz->questions()->sum('points')]);

        return redirect()->route('enseignant.quiz.show', $question->quiz)->with('success', 'Question mise à jour.');
    }

    public function destroyQuestion(Question $question)
    {
        $this->authorize('update', $question->quiz->cours);
        $quiz = $question->quiz;
        $question->delete();

        // Auto-update note_max to the new total sum of question points
        $quiz->update(['note_max' => $quiz->questions()->sum('points')]);

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

        // Récupérer toutes les réponses pour regrouper par copie (étudiant + quiz)
        $toutesReponses = Reponse::whereHas('question.quiz.cours', function ($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->with(['etudiant.user', 'question.quiz'])->get();

        $copies = $toutesReponses->groupBy(function($reponse) {
            return $reponse->question->quiz_id . '_' . $reponse->etudiant_id;
        })->map(function($group) {
            $first = $group->first();
            $quiz = $first->question->quiz;
            $etudiant = $first->etudiant;
            
            $hasPending = $group->contains('score_obtenu', -1);
            $scoreTotal = $quiz->calculerScore($etudiant);
            
            return (object)[
                'quiz' => $quiz,
                'etudiant' => $etudiant,
                'date_soumission' => $group->max('created_at'),
                'is_pending' => $hasPending,
                'score' => $scoreTotal,
                'reponses' => $group,
            ];
        })->sortByDesc('date_soumission');

        return view('enseignant.resultats', compact('quizzes', 'resultats', 'progressions', 'copies'));
    }

    public function evaluerReponse(Request $request, Reponse $reponse)
    {
        $this->authorize('update', $reponse->question->quiz->cours);

        $request->validate([
            'score_obtenu' => 'required|integer|min:0|max:' . $reponse->question->points,
            'est_correcte' => 'required|boolean',
        ]);

        $reponse->update([
            'score_obtenu' => $request->score_obtenu,
            'est_correcte' => (bool)$request->est_correcte,
        ]);

        // Mettre à jour la progression
        $progression = Progression::where('etudiant_id', $reponse->etudiant_id)
            ->where('cours_id', $reponse->question->quiz->cours_id)
            ->first();
        if ($progression) {
            $progression->mettreAJour();
        }

        return redirect()->back()->with('success', 'Réponse évaluée avec succès.');
    }

    public function corrigerCopie(Quiz $quiz, Etudiant $etudiant)
    {
        $this->authorize('update', $quiz->cours);

        $reponses = Reponse::where('etudiant_id', $etudiant->id)
            ->whereHas('question', function ($query) use ($quiz) {
                $query->where('quiz_id', $quiz->id);
            })->with('question.choixReponses')->get();

        return view('enseignant.quiz.correction', compact('quiz', 'etudiant', 'reponses'));
    }

    public function enregistrerCorrection(Request $request, Quiz $quiz, Etudiant $etudiant)
    {
        $this->authorize('update', $quiz->cours);

        $request->validate([
            'reponses' => 'required|array',
            'reponses.*.score_obtenu' => 'required|integer|min:0',
            'reponses.*.est_correcte' => 'required|boolean',
        ]);

        foreach ($request->reponses as $reponseId => $data) {
            $reponse = Reponse::where('id', $reponseId)
                ->where('etudiant_id', $etudiant->id)
                ->first();

            if ($reponse && $reponse->question->type === 'texte_libre') {
                $maxPoints = $reponse->question->points;
                $score = min((int)$data['score_obtenu'], $maxPoints);

                $reponse->update([
                    'score_obtenu' => $score,
                    'est_correcte' => (bool)$data['est_correcte'],
                ]);
            }
        }

        // Mettre à jour la progression de l'étudiant pour ce cours
        $progression = Progression::firstOrCreate(
            ['etudiant_id' => $etudiant->id, 'cours_id' => $quiz->cours_id],
            ['pourcentage' => 0]
        );
        $progression->mettreAJour();

        return redirect()->route('enseignant.resultats')->with('success', 'La correction de la copie a été enregistrée avec succès.');
    }
}