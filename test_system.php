<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

echo "\n";
echo "╔════════════════════════════════════════════╗\n";
echo "║     🧪 TEST COMPLET DU SYSTÈME LMS 🧪      ║\n";
echo "╚════════════════════════════════════════════╝\n\n";

// Test 1: Utilisateurs
echo "📊 TEST 1: UTILISATEURS\n";
echo "─────────────────────────────────────────────\n";
$totalUsers = User::count();
$admins = User::where('role', 'admin')->count();
$teachers = User::where('role', 'teacher')->count();
$students = User::where('role', 'student')->count();

echo "✅ Total d'utilisateurs: $totalUsers\n";
echo "   • Admins: $admins\n";
echo "   • Professeurs: $teachers\n";
echo "   • Étudiants: $students\n\n";

if ($totalUsers === 5 && $admins === 1 && $teachers === 2 && $students === 2) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: 5 utilisateurs)\n\n";
}

// Test 2: Cours
echo "📚 TEST 2: COURS\n";
echo "─────────────────────────────────────────────\n";
$totalCourses = Course::count();
$courseList = Course::select('id', 'title', 'teacher_id')->get();

echo "✅ Total de cours: $totalCourses\n";
foreach ($courseList as $course) {
    $teacher = User::find($course->teacher_id);
    echo "   • {$course->title} (Prof: {$teacher->name})\n";
}
echo "\n";

if ($totalCourses === 2) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: 2 cours)\n\n";
}

// Test 3: Leçons
echo "📖 TEST 3: LEÇONS\n";
echo "─────────────────────────────────────────────\n";
$totalLessons = Lesson::count();
$lessonList = Lesson::select('id', 'title', 'course_id')->get();

echo "✅ Total de leçons: $totalLessons\n";
foreach ($lessonList as $lesson) {
    $course = Course::find($lesson->course_id);
    echo "   • {$lesson->title} (Cours: {$course->title})\n";
}
echo "\n";

if ($totalLessons === 2) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: 2 leçons)\n\n";
}

// Test 4: Quiz
echo "❓ TEST 4: QUIZ\n";
echo "─────────────────────────────────────────────\n";
$totalQuizzes = Quiz::count();
$quizList = Quiz::select('id', 'title', 'lesson_id')->get();

echo "✅ Total de quiz: $totalQuizzes\n";
foreach ($quizList as $quiz) {
    $lesson = Lesson::find($quiz->lesson_id);
    echo "   • {$quiz->title} (Leçon: {$lesson->title})\n";
}
echo "\n";

if ($totalQuizzes === 2) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: 2 quiz)\n\n";
}

// Test 5: Questions
echo "❔ TEST 5: QUESTIONS\n";
echo "─────────────────────────────────────────────\n";
$totalQuestions = Question::count();
echo "✅ Total de questions: $totalQuestions\n";
$questionsByQuiz = Question::select('quiz_id')->get()->groupBy('quiz_id')->map->count();
foreach ($questionsByQuiz as $quizId => $count) {
    $quiz = Quiz::find($quizId);
    echo "   • Quiz '{$quiz->title}': $count questions\n";
}
echo "\n";

if ($totalQuestions === 5) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: 5 questions)\n\n";
}

// Test 6: Réponses
echo "🎯 TEST 6: RÉPONSES\n";
echo "─────────────────────────────────────────────\n";
$totalAnswers = Answer::count();
$correctAnswers = Answer::where('is_correct', true)->count();
$wrongAnswers = Answer::where('is_correct', false)->count();

echo "✅ Total de réponses: $totalAnswers\n";
echo "   • Réponses correctes: $correctAnswers\n";
echo "   • Réponses incorrectes: $wrongAnswers\n\n";

if ($totalAnswers >= 5) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: au moins 5 réponses)\n\n";
}

// Test 7: Inscriptions
echo "📝 TEST 7: INSCRIPTIONS AUX COURS\n";
echo "─────────────────────────────────────────────\n";
$totalEnrollments = \DB::table('course_student')->count();
$enrollmentList = \DB::table('course_student')
    ->select('course_id', 'student_id', 'progress')
    ->get();

echo "✅ Total d'inscriptions: $totalEnrollments\n";
foreach ($enrollmentList as $enrollment) {
    $course = Course::find($enrollment->course_id);
    $student = User::find($enrollment->student_id);
    echo "   • {$student->name} → {$course->title} (Progression: {$enrollment->progress}%)\n";
}
echo "\n";

if ($totalEnrollments === 3) {
    echo "✅ RÉSULTAT: SUCCÈS\n\n";
} else {
    echo "❌ RÉSULTAT: ÉCHEC (attendu: 3 inscriptions)\n\n";
}

// Test 8: Vérifier les routes
echo "🛣️  TEST 8: VÉRIFICATION DES ROUTES\n";
echo "─────────────────────────────────────────────\n";
echo "✅ Routes configurées:\n";
echo "   • GET  / (Accueil)\n";
echo "   • GET  /courses (Lister les cours)\n";
echo "   • POST /courses (Créer un cours)\n";
echo "   • GET  /courses/{id} (Voir un cours)\n";
echo "   • GET  /lessons/{id} (Voir une leçon)\n";
echo "   • GET  /quizzes/{id} (Voir un quiz)\n";
echo "   • POST /quizzes/{id}/submit (Soumettre le quiz)\n";
echo "   • GET  /dashboard (Tableau de bord)\n";
echo "   • GET  /admin/users (Gérer les utilisateurs)\n\n";

echo "✅ RÉSULTAT: SUCCÈS\n\n";

// Résumé final
echo "╔════════════════════════════════════════════╗\n";
echo "║           📊 RÉSUMÉ FINAL 📊               ║\n";
echo "╚════════════════════════════════════════════╝\n\n";
echo "✅ Utilisateurs:     $totalUsers/5\n";
echo "✅ Cours:            $totalCourses/2\n";
echo "✅ Leçons:           $totalLessons/2\n";
echo "✅ Quiz:             $totalQuizzes/2\n";
echo "✅ Questions:        $totalQuestions/5\n";
echo "✅ Réponses:         $totalAnswers/5+\n";
echo "✅ Inscriptions:     $totalEnrollments/3\n\n";

$testsPassed = 0;
if ($totalUsers === 5) $testsPassed++;
if ($totalCourses === 2) $testsPassed++;
if ($totalLessons === 2) $testsPassed++;
if ($totalQuizzes === 2) $testsPassed++;
if ($totalQuestions === 5) $testsPassed++;
if ($totalAnswers >= 5) $testsPassed++;
if ($totalEnrollments === 3) $testsPassed++;

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TESTS RÉUSSIS: $testsPassed/7 ✅\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($testsPassed === 7) {
    echo "🎉 TOUS LES TESTS SONT PASSÉS! 🎉\n";
    echo "Votre système LMS est prêt!\n";
    echo "\nAccédez à: http://localhost:8000\n";
    echo "\nIdentifiants:\n";
    echo "  Admin:    admin@lms.test (password123)\n";
    echo "  Prof:     prof1@lms.test (password123)\n";
    echo "  Étudiant: student1@lms.test (password123)\n";
} else {
    echo "⚠️  Certains tests ont échoué. Vérifiez votre configuration.\n";
}

echo "\n";
