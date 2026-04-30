<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Course $course, Lesson $lesson)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->enrolledIn($course->id) && !$lesson->is_free) {
            abort(403, 'Vous n\'êtes pas inscrit à ce cours.');
        }

        $quiz = $lesson->quiz;
        if (!$quiz) {
            abort(404, 'Aucun quiz pour cette leçon.');
        }

        $attempts = $quiz->attempts()->where('student_id', $user->id)->get();
        
        return view('quizzes.index', compact('course', 'lesson', 'quiz', 'attempts'));
    }

    public function show(Course $course, Lesson $lesson, Quiz $quiz)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->enrolledIn($course->id) && !$lesson->is_free) {
            abort(403, 'Accès refusé.');
        }

        $questions = $quiz->questions()->with('answers')->get();
        
        return view('quizzes.show', compact('course', 'lesson', 'quiz', 'questions'));
    }

    public function startAttempt(Course $course, Lesson $lesson, Quiz $quiz)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->enrolledIn($course->id)) {
            abort(403, 'Vous n\'êtes pas inscrit.');
        }

        // Vérifier si une tentative est déjà en cours
        $activeAttempt = $quiz->attempts()
            ->where('student_id', $user->id)
            ->whereNull('finished_at')
            ->first();

        if (!$activeAttempt) {
            $activeAttempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $user->id,
                'started_at' => now()
            ]);
        }

        $questions = $quiz->questions()->with('answers')->get();
        
        return view('quizzes.attempt', compact('course', 'lesson', 'quiz', 'questions', 'activeAttempt'));
    }

    public function submitAttempt(Request $request, Course $course, Lesson $lesson, Quiz $quiz)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->enrolledIn($course->id)) {
            abort(403, 'Accès refusé.');
        }

        $attempt = QuizAttempt::findOrFail($request->input('attempt_id'));
        
        if ($attempt->student_id !== $user->id || $attempt->quiz_id !== $quiz->id) {
            abort(403, 'Tentative invalide.');
        }

        // Traiter les réponses
        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $quiz->questions()->count();

        foreach ($answers as $questionId => $answerId) {
            $question = Question::find($questionId);
            $answer = $question->answers()->find($answerId);

            $isCorrect = $answer && $answer->is_correct;
            
            if ($isCorrect) {
                $correctCount++;
            }

            AttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'selected_answer_id' => $answerId,
                'is_correct' => $isCorrect
            ]);
        }

        // Calculer le score
        $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
        
        $attempt->update([
            'finished_at' => now(),
            'score' => $score
        ]);

        // Mettre à jour la progression du cours
        LessonController::updateProgressOnQuizSubmit($course, $user);

        return redirect()->route('quiz.result', [
            'course' => $course->id,
            'lesson' => $lesson->id,
            'quiz' => $quiz->id,
            'attempt' => $attempt->id
        ])->with('success', 'Quiz soumis avec succès!');
    }

    public function viewResult(Course $course, Lesson $lesson, Quiz $quiz, QuizAttempt $attempt)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($attempt->student_id !== $user->id && !$user->isAdmin() && $user->id !== $course->teacher_id) {
            abort(403, 'Accès refusé.');
        }

        $questions = $quiz->questions()->with('answers')->get();
        $attemptAnswers = $attempt->answers()->get();
        
        return view('quizzes.result', compact('course', 'lesson', 'quiz', 'attempt', 'questions', 'attemptAnswers'));
    }

    public function create(Course $course, Lesson $lesson)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->id !== $course->teacher_id && !$user->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé.');
        }

        return view('quizzes.create', compact('course', 'lesson'));
    }

    public function store(Request $request, Course $course, Lesson $lesson)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->id !== $course->teacher_id && !$user->isAdmin()) {
            abort(403, 'Non autorisé.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|numeric|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1'
        ]);

        $validated['lesson_id'] = $lesson->id;

        Quiz::create($validated);

        return redirect()->route('lessons.show', [$course, $lesson])
                         ->with('success', 'Quiz créé avec succès!');
    }

    public function destroy(Course $course, Lesson $lesson, Quiz $quiz)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->id !== $course->teacher_id && !$user->isAdmin()) {
            abort(403, 'Non autorisé.');
        }

        $quiz->delete();

        return redirect()->route('lessons.show', [$course, $lesson])
                         ->with('success', 'Quiz supprimé!');
    }
}

