<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ========== AFFICHAGE POUR LES ÉTUDIANTS ==========
    public function show(Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        
        // Vérifier si l'étudiant est inscrit au cours
        $isEnrolled = $user->studentCourses()->where('course_id', $course->id)->exists();
        
        // Si pas inscrit et la leçon n'est pas gratuite, refuser l'accès
        if (!$isEnrolled && !$lesson->is_free) {
            abort(403, 'Vous devez être inscrit au cours pour accéder à cette leçon.');
        }
        
        // Vérifier que le cours est publié
        if (!$course->is_published && auth()->id() !== $course->teacher_id && !$user->isAdmin()) {
            abort(403, 'Ce cours n\'est pas publié.');
        }
        
        // Mettre à jour la progression si l'étudiant est inscrit
        if ($isEnrolled && $user->isStudent()) {
            $this->updateProgress($course, $user);
        }
        
        $course->load('teacher');
        $lesson->load('quiz.questions');
        
        return view('lessons.show', compact('lesson', 'course'));
    }

    // ========== CRUD POUR LES ENSEIGNANTS ==========
    public function create(Course $course)
    {
        // Vérifier que l'utilisateur est le professeur du cours
        if (auth()->id() !== $course->teacher_id && !auth()->user()->isAdmin()) {
            abort(403, 'Non autorisé.');
        }
        return view('lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        // Vérifier que l'utilisateur est le professeur du cours
        if (auth()->id() !== $course->teacher_id && !auth()->user()->isAdmin()) {
            abort(403, 'Non autorisé.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'order' => 'required|integer|min:1',
            'is_free' => 'boolean'
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins 10 caractères.',
            'order.required' => 'L\'ordre est obligatoire.'
        ]);
        
        $validated['course_id'] = $course->id;
        $validated['is_free'] = $request->boolean('is_free', false);
        
        Lesson::create($validated);
        
        return redirect()->route('teacher.courses.edit', $course)->with('success', 'Leçon créée avec succès!');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        // Vérifier que l'utilisateur est le professeur du cours
        if (auth()->id() !== $course->teacher_id && !auth()->user()->isAdmin()) {
            abort(403, 'Non autorisé.');
        }
        return view('lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Vérifier que l'utilisateur est le professeur du cours
        if (auth()->id() !== $course->teacher_id && !auth()->user()->isAdmin()) {
            abort(403, 'Non autorisé.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'order' => 'required|integer|min:1',
            'is_free' => 'boolean'
        ]);
        
        $validated['is_free'] = $request->boolean('is_free', false);
        $lesson->update($validated);
        
        return redirect()->route('lessons.show', [$course, $lesson])->with('success', 'Leçon mise à jour avec succès!');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        // Vérifier que l'utilisateur est le professeur du cours
        if (auth()->id() !== $course->teacher_id && !auth()->user()->isAdmin()) {
            abort(403, 'Non autorisé.');
        }
        
        $lesson->delete();
        
        return redirect()->route('teacher.courses.edit', $course)->with('success', 'Leçon supprimée avec succès!');
    }

    // ========== PROGRESS TRACKING ==========
    private function updateProgress(Course $course, $user)
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) {
            return;
        }

        // Récupérer les leçons visitées (on considère une leçon comme "visitée" si elle a été view)
        $viewedLessons = session("course_{$course->id}_lessons", []);
        $viewedCount = count($viewedLessons);
        
        $progress = $totalLessons > 0 ? round(($viewedCount / $totalLessons) * 100) : 0;
        
        // Mettre à jour le pivot
        $user->studentCourses()->updateExistingPivot($course->id, [
            'progress' => min($progress, 100)
        ]);
    }

    public static function updateProgressOnQuizSubmit(Course $course, $user)
    {
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) {
            return;
        }

        // Récupérer les quizzes réussis
        $passedQuizzes = $user->quizAttempts()
            ->whereIn('quiz_id', $course->lessons()->pluck('quiz_id'))
            ->where('score', '>=', 60)
            ->distinct('quiz_id')
            ->count();

        // Calculer la progression basée sur les leçons + quizzes
        $lessonsWithQuiz = $course->lessons()->whereNotNull('quiz_id')->count();
        $lessonsWithoutQuiz = $totalLessons - $lessonsWithQuiz;
        
        // 50% pour les leçons, 50% pour les quizzes
        $lessonProgress = $lessonsWithoutQuiz > 0 ? (($lessonsWithoutQuiz / $totalLessons) * 50) : 0;
        $quizProgress = $lessonsWithQuiz > 0 ? (($passedQuizzes / $lessonsWithQuiz) * 50) : 0;
        
        $progress = min(round($lessonProgress + $quizProgress), 100);
        
        $user->studentCourses()->updateExistingPivot($course->id, [
            'progress' => $progress
        ]);
    }
}

