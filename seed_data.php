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
use Illuminate\Support\Facades\DB;

try {
    // Vider les données
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    User::truncate();
    Course::truncate();
    Lesson::truncate();
    Quiz::truncate();
    Question::truncate();
    Answer::truncate();
    DB::table('course_student')->truncate();
    DB::table('quiz_attempts')->truncate();
    DB::table('attempt_answers')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    // Admin
    $admin = User::create([
        'name' => 'Administrateur',
        'email' => 'admin@lms.test',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    // Profs
    $prof1 = User::create([
        'name' => 'Dr. Ahmed Karim',
        'email' => 'prof1@lms.test',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
    ]);

    $prof2 = User::create([
        'name' => 'Mme Fatima Saïd',
        'email' => 'prof2@lms.test',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
    ]);

    // Étudiants
    $student1 = User::create([
        'name' => 'Ali Mohammed',
        'email' => 'student1@lms.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    $student2 = User::create([
        'name' => 'Leila Hassan',
        'email' => 'student2@lms.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
    ]);

    // Cours
    $course1 = Course::create([
        'title' => 'Introduction à Laravel',
        'description' => 'Apprenez les bases du framework Laravel',
        'teacher_id' => $prof1->id,
    ]);

    $course2 = Course::create([
        'title' => 'Mathématiques Avancées',
        'description' => 'Algèbre linéaire et analyse',
        'teacher_id' => $prof2->id,
    ]);

    // Leçons
    $lesson1 = Lesson::create([
        'title' => 'Installation et Configuration',
        'content' => 'Comment installer et configurer Laravel correctement',
        'course_id' => $course1->id,
    ]);

    $lesson2 = Lesson::create([
        'title' => 'Routing et Controllers',
        'content' => 'Comprendre le système de routage Laravel',
        'course_id' => $course1->id,
    ]);

    // Quiz
    $quiz1 = Quiz::create([
        'title' => 'Quiz 1: Concepts de base',
        'lesson_id' => $lesson1->id,
    ]);

    $quiz2 = Quiz::create([
        'title' => 'Quiz 2: Routing',
        'lesson_id' => $lesson2->id,
    ]);

    // Questions Quiz 1
    $q1 = Question::create([
        'quiz_id' => $quiz1->id,
        'question_text' => 'Quel est le port par défaut de Laravel serve?',
        'type' => 'multiple_choice',
    ]);

    Answer::create(['question_id' => $q1->id, 'answer_text' => '8000', 'is_correct' => true]);
    Answer::create(['question_id' => $q1->id, 'answer_text' => '3000', 'is_correct' => false]);
    Answer::create(['question_id' => $q1->id, 'answer_text' => '5000', 'is_correct' => false]);

    $q2 = Question::create([
        'quiz_id' => $quiz1->id,
        'question_text' => 'Qu\'est-ce qu\'un middleware en Laravel?',
        'type' => 'multiple_choice',
    ]);

    Answer::create(['question_id' => $q2->id, 'answer_text' => 'Un filtre de requête HTTP', 'is_correct' => true]);
    Answer::create(['question_id' => $q2->id, 'answer_text' => 'Une base de données', 'is_correct' => false]);
    Answer::create(['question_id' => $q2->id, 'answer_text' => 'Une vue Blade', 'is_correct' => false]);

    // Questions Quiz 2
    $q3 = Question::create([
        'quiz_id' => $quiz2->id,
        'question_text' => 'Comment définir une route en Laravel?',
        'type' => 'multiple_choice',
    ]);

    Answer::create(['question_id' => $q3->id, 'answer_text' => 'Route::get(...)', 'is_correct' => true]);
    Answer::create(['question_id' => $q3->id, 'answer_text' => 'Route::make(...)', 'is_correct' => false]);
    Answer::create(['question_id' => $q3->id, 'answer_text' => 'Route::define(...)', 'is_correct' => false]);

    // Inscriptions aux cours
    DB::table('course_student')->insert([
        ['course_id' => $course1->id, 'student_id' => $student1->id, 'progress' => 0, 'enrolled_at' => now()],
        ['course_id' => $course1->id, 'student_id' => $student2->id, 'progress' => 0, 'enrolled_at' => now()],
        ['course_id' => $course2->id, 'student_id' => $student1->id, 'progress' => 0, 'enrolled_at' => now()],
    ]);

    echo "✅ Données créées avec succès!\n\n";
    echo "====== IDENTIFIANTS DE CONNEXION ======\n";
    echo "Admin: admin@lms.test\n";
    echo "Prof 1: prof1@lms.test\n";
    echo "Prof 2: prof2@lms.test\n";
    echo "Étudiant 1: student1@lms.test\n";
    echo "Étudiant 2: student2@lms.test\n";
    echo "Mot de passe (tous): password123\n";
    echo "======================================\n\n";
    echo "✅ Base de données remplie avec:\n";
    echo "   - 5 utilisateurs\n";
    echo "   - 2 cours\n";
    echo "   - 2 leçons\n";
    echo "   - 2 quiz\n";
    echo "   - 5 questions\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
