# 🗄️ SCRIPT COMPLET DE REMPLISSAGE DES DONNÉES

## Comment utiliser ce script

### Méthode 1: Avec Tinker (Recommandée)

```bash
php artisan tinker
```

Puis copier-coller les sections du script ci-dessous.

### Méthode 2: Créer un Seeder

```bash
php artisan make:seeder LMSSeeder
```

Puis copier le contenu dans le fichier `database/seeders/LMSSeeder.php`

---

## 📝 SCRIPT COMPLET

```php
<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;

// ============================================================
// ÉTAPE 1: VIDER LES DONNÉES EXISTANTES
// ============================================================

DB::statement('SET FOREIGN_KEY_CHECKS=0');
User::truncate();
Course::truncate();
Lesson::truncate();
Quiz::truncate();
Question::truncate();
Answer::truncate();
QuizAttempt::truncate();
AttemptAnswer::truncate();
DB::table('course_student')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// ============================================================
// ÉTAPE 2: CRÉER L'ADMINISTRATEUR
// ============================================================

$admin = User::create([
    'name' => 'Administrateur',
    'email' => 'admin@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'phone' => '0600000000',
    'bio' => 'Administrateur du système LMS'
]);

echo "✓ Admin créé: admin@lms.test\n";

// ============================================================
// ÉTAPE 3: CRÉER LES ENSEIGNANTS
// ============================================================

$prof1 = User::create([
    'name' => 'Dr. Ahmed Karim',
    'email' => 'prof1@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'teacher',
    'phone' => '0611111111',
    'specialization' => 'Informatique',
    'bio' => 'Professeur d\'informatique et développement web'
]);

$prof2 = User::create([
    'name' => 'Mme Fatima Saïd',
    'email' => 'prof2@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'teacher',
    'phone' => '0622222222',
    'specialization' => 'Mathématiques',
    'bio' => 'Professeur de mathématiques et algèbre'
]);

$prof3 = User::create([
    'name' => 'Mr. Hassan Ben',
    'email' => 'prof3@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'teacher',
    'phone' => '0633333333',
    'specialization' => 'Langues',
    'bio' => 'Professeur d\'anglais et français'
]);

echo "✓ 3 Enseignants créés\n";

// ============================================================
// ÉTAPE 4: CRÉER LES ÉTUDIANTS
// ============================================================

$students = [];
$firstNames = ['Ali', 'Fatima', 'Mohammed', 'Leila', 'Hassan', 'Aisha', 'Omar', 'Zainab', 'Ibrahim', 'Samira'];
$lastNames = ['Ahmed', 'Ben', 'El', 'Saïd', 'Hassan', 'Mohamed', 'Ibrahim', 'Karim', 'Rashid', 'Malik'];

for ($i = 0; $i < 10; $i++) {
    $student = User::create([
        'name' => $firstNames[$i] . ' ' . $lastNames[$i],
        'email' => 'student' . ($i + 1) . '@lms.test',
        'password' => bcrypt('password123'),
        'role' => 'student',
        'phone' => '0640000' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
    ]);
    $students[] = $student;
}

echo "✓ 10 Étudiants créés\n";

// ============================================================
// ÉTAPE 5: CRÉER LES COURS
// ============================================================

$courses = [];

// Cours 1: Introduction à PHP
$courses[] = Course::create([
    'title' => 'Introduction à PHP',
    'slug' => 'intro-php',
    'description' => 'Apprenez les bases du PHP moderne. Ce cours couvre les variables, les types, les fonctions et la programmation orientée objet.',
    'teacher_id' => $prof1->id,
    'category' => 'Informatique',
    'level' => 'beginner',
    'price' => 0,
    'is_published' => true
]);

// Cours 2: Laravel Avancé
$courses[] = Course::create([
    'title' => 'Développement Web Avancé avec Laravel',
    'slug' => 'laravel-avance',
    'description' => 'Maîtrisez Laravel 10 et créez des applications web professionnelles avec authentification, base de données et API REST.',
    'teacher_id' => $prof1->id,
    'category' => 'Informatique',
    'level' => 'advanced',
    'price' => 49.99,
    'is_published' => true
]);

// Cours 3: Algèbre Linéaire
$courses[] = Course::create([
    'title' => 'Algèbre Linéaire Fondamentale',
    'slug' => 'algebre-lineaire',
    'description' => 'Comprendre les matrices, vecteurs, espaces vectoriels et transformations linéaires. Essentiel pour la science des données.',
    'teacher_id' => $prof2->id,
    'category' => 'Mathématiques',
    'level' => 'intermediate',
    'price' => 29.99,
    'is_published' => true
]);

// Cours 4: Anglais Professionnel
$courses[] = Course::create([
    'title' => 'Anglais pour les Professionnels IT',
    'slug' => 'anglais-it',
    'description' => 'Améliorez votre anglais pour communiquer dans un environnement professionnel informatique.',
    'teacher_id' => $prof3->id,
    'category' => 'Langues',
    'level' => 'beginner',
    'price' => 19.99,
    'is_published' => true
]);

// Cours 5: Calcul Différentiel
$courses[] = Course::create([
    'title' => 'Calcul Différentiel et Intégral',
    'slug' => 'calcul-diff',
    'description' => 'Apprenez le calcul différentiel et intégral - fondamental pour l\'ingénierie et la physique.',
    'teacher_id' => $prof2->id,
    'category' => 'Mathématiques',
    'level' => 'intermediate',
    'price' => 34.99,
    'is_published' => true
]);

echo "✓ 5 Cours créés\n";

// ============================================================
// ÉTAPE 6: INSCRIRE LES ÉTUDIANTS AUX COURS
// ============================================================

foreach ($students as $student) {
    // Chaque étudiant s'inscrit à 2-3 cours aléatoires
    $randomCourses = collect($courses)->random(rand(2, 3));
    foreach ($randomCourses as $course) {
        $student->studentCourses()->attach($course->id, [
            'progress' => rand(0, 100),
            'enrolled_at' => now()->subDays(rand(1, 30))
        ]);
    }
}

echo "✓ Étudiants inscrits aux cours\n";

// ============================================================
// ÉTAPE 7: CRÉER LES LEÇONS POUR LE PREMIER COURS
// ============================================================

$course1 = $courses[0]; // Introduction à PHP

$lessons = [];

$lessons[] = Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Les bases de PHP',
    'description' => 'Comprendre les variables et les types de données',
    'content' => '<h2>Introduction aux variables PHP</h2><p>Une variable en PHP est un conteneur pour stocker des données...</p>',
    'order' => 1,
    'is_free' => true,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

$lessons[] = Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Structures de contrôle',
    'description' => 'If, else, switch et boucles',
    'content' => '<h2>Contrôler le flux d\'un programme</h2><p>Les structures de contrôle permettent d\'exécuter du code de façon conditionnelle...</p>',
    'order' => 2,
    'is_free' => false,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

$lessons[] = Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Fonctions en PHP',
    'description' => 'Définir et utiliser des fonctions',
    'content' => '<h2>Les fonctions</h2><p>Les fonctions sont essentielles pour organiser votre code...</p>',
    'order' => 3,
    'is_free' => false,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

$lessons[] = Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Tableaux et Boucles',
    'description' => 'Manipulation des tableaux et itération',
    'content' => '<h2>Tableaux en PHP</h2><p>Les tableaux sont des structures de données puissantes...</p>',
    'order' => 4,
    'is_free' => false,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

echo "✓ 4 Leçons créées\n";

// ============================================================
// ÉTAPE 8: CRÉER UN QUIZ POUR LA PREMIÈRE LEÇON
// ============================================================

$quiz = Quiz::create([
    'lesson_id' => $lessons[0]->id,
    'title' => 'Quiz: Les bases de PHP',
    'description' => 'Testez vos connaissances sur les variables et types en PHP',
    'passing_score' => 70,
    'time_limit' => 30
]);

echo "✓ Quiz créé\n";

// ============================================================
// ÉTAPE 9: CRÉER LES QUESTIONS ET RÉPONSES
// ============================================================

// Question 1
$q1 = Question::create([
    'quiz_id' => $quiz->id,
    'question_text' => 'Quel est le type de données pour la valeur 3.14?',
    'question_type' => 'multiple_choice',
    'order' => 1
]);

Answer::create(['question_id' => $q1->id, 'answer_text' => 'String', 'is_correct' => false, 'order' => 1]);
Answer::create(['question_id' => $q1->id, 'answer_text' => 'Float', 'is_correct' => true, 'order' => 2]);
Answer::create(['question_id' => $q1->id, 'answer_text' => 'Integer', 'is_correct' => false, 'order' => 3]);
Answer::create(['question_id' => $q1->id, 'answer_text' => 'Boolean', 'is_correct' => false, 'order' => 4]);

// Question 2
$q2 = Question::create([
    'quiz_id' => $quiz->id,
    'question_text' => 'Quelle est la syntaxe correcte pour une variable en PHP?',
    'question_type' => 'multiple_choice',
    'order' => 2
]);

Answer::create(['question_id' => $q2->id, 'answer_text' => 'var $x = 5;', 'is_correct' => false, 'order' => 1]);
Answer::create(['question_id' => $q2->id, 'answer_text' => '$x = 5;', 'is_correct' => true, 'order' => 2]);
Answer::create(['question_id' => $q2->id, 'answer_text' => 'x = 5;', 'is_correct' => false, 'order' => 3]);
Answer::create(['question_id' => $q2->id, 'answer_text' => 'define x = 5;', 'is_correct' => false, 'order' => 4]);

// Question 3
$q3 = Question::create([
    'quiz_id' => $quiz->id,
    'question_text' => 'Comment déclarer une constante en PHP?',
    'question_type' => 'multiple_choice',
    'order' => 3
]);

Answer::create(['question_id' => $q3->id, 'answer_text' => 'const MY_CONST = 10;', 'is_correct' => true, 'order' => 1]);
Answer::create(['question_id' => $q3->id, 'answer_text' => '$MY_CONST = 10;', 'is_correct' => false, 'order' => 2]);
Answer::create(['question_id' => $q3->id, 'answer_text' => 'define(\'MY_CONST\', 10);', 'is_correct' => true, 'order' => 3]);
Answer::create(['question_id' => $q3->id, 'answer_text' => 'constant MY_CONST = 10;', 'is_correct' => false, 'order' => 4]);

echo "✓ 3 Questions avec réponses créées\n";

// ============================================================
// ÉTAPE 10: CRÉER DES TENTATIVES DE QUIZ
// ============================================================

// Créer quelques tentatives
for ($i = 0; $i < 5; $i++) {
    $student = $students[$i];

    $attempt = QuizAttempt::create([
        'quiz_id' => $quiz->id,
        'student_id' => $student->id,
        'started_at' => now()->subDays(rand(1, 20)),
        'finished_at' => now()->subDays(rand(1, 20))->addMinutes(rand(5, 25)),
        'score' => rand(50, 100)
    ]);

    // Ajouter les réponses de l'étudiant
    // Réponse question 1: correct (Float)
    AttemptAnswer::create([
        'quiz_attempt_id' => $attempt->id,
        'question_id' => $q1->id,
        'selected_answer_id' => 2,
        'is_correct' => true
    ]);

    // Réponse question 2: aléatoire
    $answer2 = rand(0, 1) === 0 ? 2 : 4;
    AttemptAnswer::create([
        'quiz_attempt_id' => $attempt->id,
        'question_id' => $q2->id,
        'selected_answer_id' => $answer2,
        'is_correct' => $answer2 === 2
    ]);

    // Réponse question 3: correct
    AttemptAnswer::create([
        'quiz_attempt_id' => $attempt->id,
        'question_id' => $q3->id,
        'selected_answer_id' => rand(1, 2), // Peut être 1 ou 3 (tous les deux correct)
        'is_correct' => true
    ]);
}

echo "✓ 5 Tentatives de quiz créées\n";

// ============================================================
// RÉSUMÉ FINAL
// ============================================================

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "✅ DONNÉES CRÉÉES AVEC SUCCÈS!\n";
echo "═══════════════════════════════════════════════════════\n";
echo "✓ 1 Administrateur\n";
echo "✓ 3 Enseignants\n";
echo "✓ 10 Étudiants\n";
echo "✓ 5 Cours\n";
echo "✓ 4 Leçons\n";
echo "✓ 1 Quiz avec 3 questions\n";
echo "✓ 5 Tentatives de quiz\n";
echo "═══════════════════════════════════════════════════════\n";
echo "\n";
echo "COMPTES DE TEST:\n";
echo "Admin:  admin@lms.test / password123\n";
echo "Prof:   prof1@lms.test / password123\n";
echo "Étudiant: student1@lms.test / password123\n";
echo "═══════════════════════════════════════════════════════\n";
```

---

## 🎯 ÉTAPES D'EXÉCUTION

1. Ouvrir un terminal
2. Exécuter: `php artisan tinker`
3. Copier-coller chaque section du script
4. Attendre la confirmation de chaque étape
5. Vérifier les données dans la base

---

## 📊 VÉRIFIER LES DONNÉES

```php
// Vérifier le nombre d'utilisateurs
User::count()

// Voir les utilisateurs
User::all()

// Voir les cours
Course::all()

// Voir les leçons
Lesson::all()

// Voir les quiz
Quiz::all()

// Voir les inscrptions
DB::table('course_student')->count()

// Voir les tentatives
QuizAttempt::all()
```

---

## 🚀 LANCER L'APPLICATION

Après avoir exécuté ce script:

```bash
php artisan serve
```

Puis accédez à: **http://localhost:8000**

Et connectez-vous avec l'un des comptes de test!
