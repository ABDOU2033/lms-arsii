<?php

// Bootstrap Laravel application so this script can be run with: php scripts/seed.php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cours;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\ChoixReponse;
use App\Models\Inscription;
use App\Models\Progression;
use App\Models\Enseignant;
use App\Models\Etudiant;

echo "Starting seed script...\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0');
\App\Models\User::query()->delete();
\App\Models\Enseignant::query()->delete();
\App\Models\Etudiant::query()->delete();
\App\Models\Cours::query()->delete();
\App\Models\Lecon::query()->delete();
\App\Models\Quiz::query()->delete();
\App\Models\Question::query()->delete();
\App\Models\ChoixReponse::query()->delete();
\App\Models\Inscription::query()->delete();
\App\Models\Progression::query()->delete();
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Tables truncated. Creating records...\n";

$admin = User::create([
    'nom' => 'Admin Ahmed',
    'email' => 'admin@lms.com',
    'mot_de_passe' => bcrypt('Admin@1234'),
    'role' => 'administrateur',
    'actif' => true,
]);

$prof1 = User::create([
    'nom' => 'Fatima Zahra',
    'email' => 'fatima@lms.com',
    'mot_de_passe' => bcrypt('Fatima@2024'),
    'role' => 'enseignant',
    'actif' => true,
]);

$prof1_profile = Enseignant::create([
    'user_id' => $prof1->id,
    'specialite' => 'Informatique',
]);

$prof2 = User::create([
    'nom' => 'Nicolas Lemoine',
    'email' => 'nicolas@lms.com',
    'mot_de_passe' => bcrypt('Nicolas@2024'),
    'role' => 'enseignant',
    'actif' => true,
]);

$prof2_profile = Enseignant::create([
    'user_id' => $prof2->id,
    'specialite' => 'Mathématiques',
]);

$student1 = User::create([
    'nom' => 'Karim Abadi',
    'email' => 'etudiant@lms.com',
    'mot_de_passe' => bcrypt('Karim@2024'),
    'role' => 'etudiant',
    'actif' => true,
]);

$student1_profile = Etudiant::create([
    'user_id' => $student1->id,
    'niveau' => 'L3',
]);

$student2 = User::create([
    'nom' => 'Sofia Ghezzi',
    'email' => 'sofia@lms.com',
    'mot_de_passe' => bcrypt('Sofia@2024'),
    'role' => 'etudiant',
    'actif' => true,
]);

$student2_profile = Etudiant::create([
    'user_id' => $student2->id,
    'niveau' => 'M1',
]);

// Ajouter plus d'étudiants
$student3 = User::create([
    'nom' => 'Ahmed Mourad',
    'email' => 'ahmed.mourad@lms.com',
    'mot_de_passe' => bcrypt('Ahmed@2024'),
    'role' => 'etudiant',
    'actif' => true,
]);

$student3_profile = Etudiant::create([
    'user_id' => $student3->id,
    'niveau' => 'L2',
]);

$student4 = User::create([
    'nom' => 'Yasmine Benkaoui',
    'email' => 'yasmine@lms.com',
    'mot_de_passe' => bcrypt('Yasmine@2024'),
    'role' => 'etudiant',
    'actif' => true,
]);

$student4_profile = Etudiant::create([
    'user_id' => $student4->id,
    'niveau' => 'L3',
]);

$student5 = User::create([
    'nom' => 'Mehdi Rachid',
    'email' => 'mehdi@lms.com',
    'mot_de_passe' => bcrypt('Mehdi@2024'),
    'role' => 'etudiant',
    'actif' => true,
]);

$student5_profile = Etudiant::create([
    'user_id' => $student5->id,
    'niveau' => 'M2',
]);

$student6 = User::create([
    'nom' => 'Lina Belkaid',
    'email' => 'lina@lms.com',
    'mot_de_passe' => bcrypt('Lina@2024'),
    'role' => 'etudiant',
    'actif' => true,
]);

$student6_profile = Etudiant::create([
    'user_id' => $student6->id,
    'niveau' => 'L1',
]);

// Ajouter plus d'enseignants
$prof3 = User::create([
    'nom' => 'Mohamed Cherif',
    'email' => 'cherif@lms.com',
    'mot_de_passe' => bcrypt('Cherif@2024'),
    'role' => 'enseignant',
    'actif' => true,
]);

$prof3_profile = Enseignant::create([
    'user_id' => $prof3->id,
    'specialite' => 'Physique',
]);

$prof4 = User::create([
    'nom' => 'Aicha Benali',
    'email' => 'aicha@lms.com',
    'mot_de_passe' => bcrypt('Aicha@2024'),
    'role' => 'enseignant',
    'actif' => true,
]);

$prof4_profile = Enseignant::create([
    'user_id' => $prof4->id,
    'specialite' => 'Chimie',
]);

$prof5 = User::create([
    'nom' => 'Amine Haddad',
    'email' => 'amine.flutter@lms.com',
    'mot_de_passe' => bcrypt('Amine@2026'),
    'role' => 'enseignant',
    'actif' => true,
]);

$prof5_profile = Enseignant::create([
    'user_id' => $prof5->id,
    'specialite' => 'Flutter',
]);

$course = Cours::create([
    'titre' => 'Introduction au Laravel',
    'description' => 'Apprendre les bases du framework Laravel',
    'enseignant_id' => $prof1_profile->id,
    'statut' => 'publie',
]);

$lesson = Lecon::create([
    'titre' => 'Installation et Configuration',
    'contenu' => 'Comment installer et configurer Laravel',
    'cours_id' => $course->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$course2 = Cours::create([
    'titre' => 'Algorithmes et Structures de Données',
    'description' => 'Apprendre les bases des algorithmes',
    'enseignant_id' => $prof2_profile->id,
    'statut' => 'publie',
]);

$lesson2 = Lecon::create([
    'titre' => 'Introduction aux Algorithmes',
    'contenu' => 'Qu\'est-ce qu\'un algorithme ?',
    'cours_id' => $course2->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$quiz2 = Quiz::create([
    'cours_id' => $course2->id,
    'titre' => 'Quiz 2: Complexité',
    'duree' => 15,
    'note_max' => 5,
]);

$question2 = Question::create([
    'quiz_id' => $quiz2->id,
    'enonce' => 'Quelle est la complexité temporelle de la recherche binaire ?',
    'type' => 'qcm',
    'points' => 5,
]);

ChoixReponse::create([
    'question_id' => $question2->id,
    'contenu' => 'O(log n)',
    'est_correcte' => true,
]);

ChoixReponse::create([
    'question_id' => $question2->id,
    'contenu' => 'O(n)',
    'est_correcte' => false,
]);

ChoixReponse::create([
    'question_id' => $question2->id,
    'contenu' => 'O(n²)',
    'est_correcte' => false,
]);

// Nouveau quiz pour le cours Algorithmes et Structures de Données
$quiz2b = Quiz::create([
    'cours_id' => $course2->id,
    'titre' => 'Quiz 3: Structures de Données',
    'duree' => 20,
    'note_max' => 14,
]);

$question2b_1 = Question::create([
    'quiz_id' => $quiz2b->id,
    'enonce' => 'Quelle structure de données suit le principe LIFO (Last In, First Out) ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question2b_1->id, 'contenu' => 'Pile (Stack)', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question2b_1->id, 'contenu' => 'File (Queue)', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question2b_1->id, 'contenu' => 'Liste chaînée', 'est_correcte' => false]);

$question2b_2 = Question::create([
    'quiz_id' => $quiz2b->id,
    'enonce' => 'Un arbre binaire peut avoir au maximum 2 enfants par nœud.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question2b_2->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question2b_2->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question2b_3 = Question::create([
    'quiz_id' => $quiz2b->id,
    'enonce' => 'Quelle opération permet d\'accéder à un élément d\'un tableau en O(1) ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question2b_3->id, 'contenu' => 'Accès par index', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question2b_3->id, 'contenu' => 'Recherche linéaire', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question2b_3->id, 'contenu' => 'Tri', 'est_correcte' => false]);

$question2b_4 = Question::create([
    'quiz_id' => $quiz2b->id,
    'enonce' => 'Un graphe peut contenir des cycles.',
    'type' => 'vrai_faux',
    'points' => 4,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question2b_4->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question2b_4->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$lesson = Lecon::create([
    'titre' => 'Installation et Configuration',
    'contenu' => 'Comment installer et configurer Laravel',
    'cours_id' => $course->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$quiz = Quiz::create([
    'cours_id' => $course->id,
    'titre' => 'Quiz 1: Concepts de base',
    'duree' => 10,
    'note_max' => 1,
]);

$question = Question::create([
    'quiz_id' => $quiz->id,
    'enonce' => 'Quel est le port par défaut de php artisan serve ?',
    'type' => 'qcm',
    'points' => 1,
]);

ChoixReponse::create([
    'question_id' => $question->id,
    'contenu' => '8000',
    'est_correcte' => true,
]);

ChoixReponse::create([
    'question_id' => $question->id,
    'contenu' => '3000',
    'est_correcte' => false,
]);

// Nouveau quiz pour le cours Introduction au Laravel
$quiz1c = Quiz::create([
    'cours_id' => $course->id,
    'titre' => 'Quiz 3: Routing et Contrôleurs',
    'duree' => 15,
    'note_max' => 20,
]);

$question1c_1 = Question::create([
    'quiz_id' => $quiz1c->id,
    'enonce' => 'Quelle commande permet de créer un nouveau contrôleur dans Laravel ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question1c_1->id, 'contenu' => 'php artisan make:controller', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question1c_1->id, 'contenu' => 'php artisan create:controller', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question1c_1->id, 'contenu' => 'php artisan new:controller', 'est_correcte' => false]);

$question1c_2 = Question::create([
    'quiz_id' => $quiz1c->id,
    'enonce' => 'Les routes dans Laravel sont définies dans le fichier web.php.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question1c_2->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question1c_2->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question1c_3 = Question::create([
    'quiz_id' => $quiz1c->id,
    'enonce' => 'Quelle méthode HTTP est utilisée pour mettre à jour des données ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question1c_3->id, 'contenu' => 'PUT', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question1c_3->id, 'contenu' => 'GET', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question1c_3->id, 'contenu' => 'POST', 'est_correcte' => false]);

$question1c_4 = Question::create([
    'quiz_id' => $quiz1c->id,
    'enonce' => 'Les middlewares peuvent filtrer les requêtes HTTP.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question1c_4->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question1c_4->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question1c_5 = Question::create([
    'quiz_id' => $quiz1c->id,
    'enonce' => 'Quelle façade permet d\'accéder à la session dans Laravel ?',
    'type' => 'qcm',
    'points' => 6,
]);

ChoixReponse::create(['question_id' => $question1c_5->id, 'contenu' => 'Session', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question1c_5->id, 'contenu' => 'Request', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question1c_5->id, 'contenu' => 'Response', 'est_correcte' => false]);

// Add more courses, lessons, quizzes, questions

$course3 = Cours::create([
    'titre' => 'Développement Web',
    'description' => 'Apprendre HTML, CSS, JavaScript et PHP',
    'enseignant_id' => $prof1_profile->id,
    'statut' => 'publie',
]);

$lesson3_1 = Lecon::create([
    'titre' => 'Introduction à HTML',
    'contenu' => 'Les bases du langage HTML pour structurer les pages web.',
    'cours_id' => $course3->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$lesson3_2 = Lecon::create([
    'titre' => 'CSS pour le style',
    'contenu' => 'Utiliser CSS pour styliser les pages web.',
    'cours_id' => $course3->id,
    'type' => 'texte',
    'ordre' => 2,
]);

$quiz3_1 = Quiz::create([
    'cours_id' => $course3->id,
    'titre' => 'Quiz HTML de base',
    'duree' => 20,
    'note_max' => 15,
]);

// QCM question
$question3_1 = Question::create([
    'quiz_id' => $quiz3_1->id,
    'enonce' => 'Quelle balise HTML est utilisée pour créer un paragraphe ?',
    'type' => 'qcm',
    'points' => 3,
]);

ChoixReponse::create(['question_id' => $question3_1->id, 'contenu' => '<p>', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_1->id, 'contenu' => '<div>', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_1->id, 'contenu' => '<span>', 'est_correcte' => false]);

// Vrai/Faux question
$question3_2 = Question::create([
    'quiz_id' => $quiz3_1->id,
    'enonce' => 'La balise <img> nécessite un attribut alt pour l\'accessibilité.',
    'type' => 'vrai_faux',
    'points' => 2,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question3_2->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_2->id, 'contenu' => 'Faux', 'est_correcte' => false]);

// Another QCM
$question3_3 = Question::create([
    'quiz_id' => $quiz3_1->id,
    'enonce' => 'Quelle propriété CSS change la couleur du texte ?',
    'type' => 'qcm',
    'points' => 3,
]);

ChoixReponse::create(['question_id' => $question3_3->id, 'contenu' => 'color', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_3->id, 'contenu' => 'background-color', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_3->id, 'contenu' => 'font-size', 'est_correcte' => false]);

// Another Vrai/Faux
$question3_4 = Question::create([
    'quiz_id' => $quiz3_1->id,
    'enonce' => 'JavaScript est un langage côté serveur.',
    'type' => 'vrai_faux',
    'points' => 2,
    'correct_vrai_faux' => false,
]);

ChoixReponse::create(['question_id' => $question3_4->id, 'contenu' => 'Vrai', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_4->id, 'contenu' => 'Faux', 'est_correcte' => true]);

// Another QCM
$question3_5 = Question::create([
    'quiz_id' => $quiz3_1->id,
    'enonce' => 'Quelle méthode JavaScript permet d\'ajouter un élément à la fin d\'un tableau ?',
    'type' => 'qcm',
    'points' => 5,
]);

ChoixReponse::create(['question_id' => $question3_5->id, 'contenu' => 'push()', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_5->id, 'contenu' => 'pop()', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_5->id, 'contenu' => 'shift()', 'est_correcte' => false]);

// Nouveau quiz pour le cours Développement Web
$quiz3_2 = Quiz::create([
    'cours_id' => $course3->id,
    'titre' => 'Quiz CSS et JavaScript Avancé',
    'duree' => 25,
    'note_max' => 20,
]);

$question3_6 = Question::create([
    'quiz_id' => $quiz3_2->id,
    'enonce' => 'Quelle propriété CSS permet de créer des animations ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question3_6->id, 'contenu' => 'animation', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_6->id, 'contenu' => 'transition', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_6->id, 'contenu' => 'transform', 'est_correcte' => false]);

$question3_7 = Question::create([
    'quiz_id' => $quiz3_2->id,
    'enonce' => 'Les closures en JavaScript permettent d\'accéder aux variables du scope parent.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question3_7->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_7->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question3_8 = Question::create([
    'quiz_id' => $quiz3_2->id,
    'enonce' => 'Quelle méthode JavaScript permet de convertir une chaîne en nombre ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question3_8->id, 'contenu' => 'parseInt()', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_8->id, 'contenu' => 'toString()', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_8->id, 'contenu' => 'split()', 'est_correcte' => false]);

$question3_9 = Question::create([
    'quiz_id' => $quiz3_2->id,
    'enonce' => 'Le modèle boîte (box model) en CSS inclut les marges intérieures.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question3_9->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_9->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question3_10 = Question::create([
    'quiz_id' => $quiz3_2->id,
    'enonce' => 'Quelle pseudo-classe CSS sélectionne le premier enfant d\'un élément ?',
    'type' => 'qcm',
    'points' => 6,
]);

ChoixReponse::create(['question_id' => $question3_10->id, 'contenu' => ':first-child', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question3_10->id, 'contenu' => ':first', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question3_10->id, 'contenu' => ':child-first', 'est_correcte' => false]);

$course4 = Cours::create([
    'titre' => 'Base de Données',
    'description' => 'Apprendre SQL et la gestion des bases de données',
    'enseignant_id' => $prof2_profile->id,
    'statut' => 'publie',
]);

$lesson4_1 = Lecon::create([
    'titre' => 'Introduction aux Bases de Données',
    'contenu' => 'Concepts fondamentaux des bases de données relationnelles.',
    'cours_id' => $course4->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$lesson4_2 = Lecon::create([
    'titre' => 'Langage SQL',
    'contenu' => 'Apprendre les requêtes SQL de base.',
    'cours_id' => $course4->id,
    'type' => 'texte',
    'ordre' => 2,
]);

$quiz4_1 = Quiz::create([
    'cours_id' => $course4->id,
    'titre' => 'Quiz SQL Fondamental',
    'duree' => 25,
    'note_max' => 20,
]);

// QCM question
$question4_1 = Question::create([
    'quiz_id' => $quiz4_1->id,
    'enonce' => 'Quelle commande SQL permet de sélectionner des données ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question4_1->id, 'contenu' => 'SELECT', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_1->id, 'contenu' => 'INSERT', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_1->id, 'contenu' => 'UPDATE', 'est_correcte' => false]);

// Vrai/Faux
$question4_2 = Question::create([
    'quiz_id' => $quiz4_1->id,
    'enonce' => 'La clause WHERE est obligatoire dans une requête SELECT.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => false,
]);

ChoixReponse::create(['question_id' => $question4_2->id, 'contenu' => 'Vrai', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_2->id, 'contenu' => 'Faux', 'est_correcte' => true]);

// QCM
$question4_3 = Question::create([
    'quiz_id' => $quiz4_1->id,
    'enonce' => 'Quelle commande permet d\'insérer de nouvelles données ?',
    'type' => 'qcm',
    'points' => 4,
]);

ChoixReponse::create(['question_id' => $question4_3->id, 'contenu' => 'INSERT INTO', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_3->id, 'contenu' => 'SELECT FROM', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_3->id, 'contenu' => 'DELETE FROM', 'est_correcte' => false]);

// Vrai/Faux
$question4_4 = Question::create([
    'quiz_id' => $quiz4_1->id,
    'enonce' => 'Une clé primaire peut être composée de plusieurs colonnes.',
    'type' => 'vrai_faux',
    'points' => 3,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question4_4->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_4->id, 'contenu' => 'Faux', 'est_correcte' => false]);

// QCM
$question4_5 = Question::create([
    'quiz_id' => $quiz4_1->id,
    'enonce' => 'Quelle jointure retourne toutes les lignes de la table gauche ?',
    'type' => 'qcm',
    'points' => 6,
]);

ChoixReponse::create(['question_id' => $question4_5->id, 'contenu' => 'LEFT JOIN', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_5->id, 'contenu' => 'INNER JOIN', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_5->id, 'contenu' => 'RIGHT JOIN', 'est_correcte' => false]);

// Nouveau quiz pour le cours Base de Données
$quiz4_2 = Quiz::create([
    'cours_id' => $course4->id,
    'titre' => 'Quiz Avancé SQL',
    'duree' => 30,
    'note_max' => 25,
]);

$question4_6 = Question::create([
    'quiz_id' => $quiz4_2->id,
    'enonce' => 'Quelle commande SQL permet de modifier la structure d\'une table ?',
    'type' => 'qcm',
    'points' => 5,
]);

ChoixReponse::create(['question_id' => $question4_6->id, 'contenu' => 'ALTER TABLE', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_6->id, 'contenu' => 'MODIFY TABLE', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_6->id, 'contenu' => 'CHANGE TABLE', 'est_correcte' => false]);

$question4_7 = Question::create([
    'quiz_id' => $quiz4_2->id,
    'enonce' => 'Les transactions SQL garantissent l\'atomicité des opérations.',
    'type' => 'vrai_faux',
    'points' => 4,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question4_7->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_7->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question4_8 = Question::create([
    'quiz_id' => $quiz4_2->id,
    'enonce' => 'Quelle clause permet de regrouper des lignes ayant des valeurs identiques ?',
    'type' => 'qcm',
    'points' => 5,
]);

ChoixReponse::create(['question_id' => $question4_8->id, 'contenu' => 'GROUP BY', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_8->id, 'contenu' => 'ORDER BY', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_8->id, 'contenu' => 'HAVING', 'est_correcte' => false]);

$question4_9 = Question::create([
    'quiz_id' => $quiz4_2->id,
    'enonce' => 'Les vues SQL sont des tables virtuelles stockées physiquement.',
    'type' => 'vrai_faux',
    'points' => 4,
    'correct_vrai_faux' => false,
]);

ChoixReponse::create(['question_id' => $question4_9->id, 'contenu' => 'Vrai', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_9->id, 'contenu' => 'Faux', 'est_correcte' => true]);

$question4_10 = Question::create([
    'quiz_id' => $quiz4_2->id,
    'enonce' => 'Quelle commande permet de supprimer définitivement une table ?',
    'type' => 'qcm',
    'points' => 7,
]);

ChoixReponse::create(['question_id' => $question4_10->id, 'contenu' => 'DROP TABLE', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question4_10->id, 'contenu' => 'DELETE TABLE', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question4_10->id, 'contenu' => 'REMOVE TABLE', 'est_correcte' => false]);

// Cours pour Mohamed Cherif (Physique)
$course5 = Cours::create([
    'titre' => 'Mécanique Classique',
    'description' => 'Apprendre les fondamentaux de la mécanique',
    'enseignant_id' => $prof3_profile->id,
    'statut' => 'publie',
]);

$lesson5_1 = Lecon::create([
    'titre' => 'Les lois de Newton',
    'contenu' => 'Introduction aux trois lois du mouvement de Newton',
    'cours_id' => $course5->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$lesson5_2 = Lecon::create([
    'titre' => 'Cinématique',
    'contenu' => 'Étude du mouvement sans considérer les forces',
    'cours_id' => $course5->id,
    'type' => 'texte',
    'ordre' => 2,
]);

$quiz5 = Quiz::create([
    'cours_id' => $course5->id,
    'titre' => 'Quiz: Lois de Newton',
    'duree' => 20,
    'note_max' => 10,
]);

$question5_1 = Question::create([
    'quiz_id' => $quiz5->id,
    'enonce' => 'La deuxième loi de Newton énonce que F = ma.',
    'type' => 'vrai_faux',
    'points' => 5,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question5_1->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question5_1->id, 'contenu' => 'Faux', 'est_correcte' => false]);

$question5_2 = Question::create([
    'quiz_id' => $quiz5->id,
    'enonce' => 'Quelle est l\'unité SI de la force ?',
    'type' => 'qcm',
    'points' => 5,
]);

ChoixReponse::create(['question_id' => $question5_2->id, 'contenu' => 'Newton (N)', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question5_2->id, 'contenu' => 'Joule (J)', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question5_2->id, 'contenu' => 'Watt (W)', 'est_correcte' => false]);

// Cours pour Aicha Benali (Chimie)
$course6 = Cours::create([
    'titre' => 'Chimie Organique',
    'description' => 'Les principes de la chimie organique',
    'enseignant_id' => $prof4_profile->id,
    'statut' => 'publie',
]);

$lesson6_1 = Lecon::create([
    'titre' => 'Atomes et liaisons',
    'contenu' => 'Comprendre la structure atomique et les types de liaisons chimiques',
    'cours_id' => $course6->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$lesson6_2 = Lecon::create([
    'titre' => 'Les alcools et les aldéhydes',
    'contenu' => 'Propriétés et réactions des alcools et aldéhydes',
    'cours_id' => $course6->id,
    'type' => 'texte',
    'ordre' => 2,
]);

$quiz6 = Quiz::create([
    'cours_id' => $course6->id,
    'titre' => 'Quiz: Chimie Organique Basique',
    'duree' => 25,
    'note_max' => 10,
]);

$question6_1 = Question::create([
    'quiz_id' => $quiz6->id,
    'enonce' => 'Quelle est la formule générale des alcools ?',
    'type' => 'qcm',
    'points' => 5,
]);

ChoixReponse::create(['question_id' => $question6_1->id, 'contenu' => 'R-OH', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question6_1->id, 'contenu' => 'R-CHO', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question6_1->id, 'contenu' => 'R-COOH', 'est_correcte' => false]);

$question6_2 = Question::create([
    'quiz_id' => $quiz6->id,
    'enonce' => 'La liaison covalente est basée sur le partage d\'électrons.',
    'type' => 'vrai_faux',
    'points' => 5,
    'correct_vrai_faux' => true,
]);

ChoixReponse::create(['question_id' => $question6_2->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question6_2->id, 'contenu' => 'Faux', 'est_correcte' => false]);

// Cours Flutter pour Amine Haddad
$course7 = Cours::create([
    'titre' => 'Développement Flutter',
    'description' => 'Apprendre les bases de Flutter et créer une application mobile.',
    'enseignant_id' => $prof5_profile->id,
    'statut' => 'publie',
]);

$lesson7_1 = Lecon::create([
    'titre' => 'Architecture Flutter',
    'contenu' => 'Vue d\'ensemble du projet Flutter et des dossiers principaux.',
    'cours_id' => $course7->id,
    'type' => 'texte',
    'ordre' => 1,
]);

$lesson7_2 = Lecon::create([
    'titre' => 'Gestion des dépendances',
    'contenu' => 'Comment utiliser pubspec.yaml pour ajouter des packages.',
    'cours_id' => $course7->id,
    'type' => 'texte',
    'ordre' => 2,
]);

$quiz7 = Quiz::create([
    'cours_id' => $course7->id,
    'titre' => 'Quiz Flutter de base',
    'duree' => 10,
    'note_max' => 4,
]);

$question7_1 = Question::create([
    'quiz_id' => $quiz7->id,
    'enonce' => 'Quel est le dossier principal où se trouve le code source de votre application Flutter ?',
    'type' => 'qcm',
    'points' => 1,
]);

ChoixReponse::create(['question_id' => $question7_1->id, 'contenu' => 'assets', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_1->id, 'contenu' => 'ios', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_1->id, 'contenu' => 'lib', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question7_1->id, 'contenu' => 'test', 'est_correcte' => false]);

$question7_2 = Question::create([
    'quiz_id' => $quiz7->id,
    'enonce' => 'Où spécifiez-vous les dépendances de votre projet Flutter ?',
    'type' => 'qcm',
    'points' => 1,
]);

ChoixReponse::create(['question_id' => $question7_2->id, 'contenu' => 'Dans le fichier "main.dart"', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_2->id, 'contenu' => 'Dans le fichier "pubspec.lock".', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_2->id, 'contenu' => 'Dans le fichier "pubspec.yaml".', 'est_correcte' => true]);
ChoixReponse::create(['question_id' => $question7_2->id, 'contenu' => 'Dans le fichier "lib".', 'est_correcte' => false]);

$question7_3 = Question::create([
    'quiz_id' => $quiz7->id,
    'enonce' => 'Quelle commande utilisez-vous pour créer un nouveau projet Flutter à l\'aide de la ligne de commande ?',
    'type' => 'qcm',
    'points' => 1,
]);

ChoixReponse::create(['question_id' => $question7_3->id, 'contenu' => 'flutter init', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_3->id, 'contenu' => 'flutter start', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_3->id, 'contenu' => 'flutter generate', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_3->id, 'contenu' => 'flutter create', 'est_correcte' => true]);

$question7_4 = Question::create([
    'quiz_id' => $quiz7->id,
    'enonce' => 'Quel est le rôle du fichier "pubspec.yaml" dans un projet Flutter ?',
    'type' => 'qcm',
    'points' => 1,
]);

ChoixReponse::create(['question_id' => $question7_4->id, 'contenu' => 'Configurer les paramètres de compilation.', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_4->id, 'contenu' => 'Définir les thèmes de l\'application.', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_4->id, 'contenu' => 'Stocker les ressources graphiques.', 'est_correcte' => false]);
ChoixReponse::create(['question_id' => $question7_4->id, 'contenu' => 'Spécifier les métadonnées du projet et ses dépendances.', 'est_correcte' => true]);

// Inscrire les nouveaux étudiants aux cours
foreach ([$student3_profile, $student4_profile, $student5_profile, $student6_profile] as $student) {
    foreach ([$course, $course2, $course3, $course4, $course5, $course6, $course7] as $cours) {
        Inscription::create([
            'etudiant_id' => $student->id,
            'cours_id' => $cours->id,
        ]);

        Progression::create([
            'etudiant_id' => $student->id,
            'cours_id' => $cours->id,
            'pourcentage' => 0,
        ]);
    }
}

Inscription::create([
    'etudiant_id' => $student1_profile->id,
    'cours_id' => $course3->id,
]);

Progression::create([
    'etudiant_id' => $student1_profile->id,
    'cours_id' => $course3->id,
    'pourcentage' => 0,
]);

Inscription::create([
    'etudiant_id' => $student1_profile->id,
    'cours_id' => $course4->id,
]);

Progression::create([
    'etudiant_id' => $student1_profile->id,
    'cours_id' => $course4->id,
    'pourcentage' => 0,
]);

Inscription::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course3->id,
]);

Progression::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course3->id,
    'pourcentage' => 0,
]);

Inscription::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course4->id,
]);

Progression::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course4->id,
    'pourcentage' => 0,
]);

Inscription::create([
    'etudiant_id' => $student1_profile->id,
    'cours_id' => $course->id,
]);

Progression::create([
    'etudiant_id' => $student1_profile->id,
    'cours_id' => $course->id,
    'pourcentage' => 0,
]);

Inscription::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course->id,
]);

Progression::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course->id,
    'pourcentage' => 0,
]);

Inscription::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course2->id,
]);

Progression::create([
    'etudiant_id' => $student2_profile->id,
    'cours_id' => $course2->id,
    'pourcentage' => 0,
]);

echo "Seeding finished. Users created:\n";
echo " - admin@lms.com / Admin@1234 (Admin Ahmed - Admin)\n";
echo " - fatima@lms.com / Fatima@2024 (Fatima Zahra - Enseignant - Informatique)\n";
echo " - nicolas@lms.com / Nicolas@2024 (Nicolas Lemoine - Enseignant - Mathématiques)\n";
echo " - cherif@lms.com / Cherif@2024 (Mohamed Cherif - Enseignant - Physique)\n";
echo " - aicha@lms.com / Aicha@2024 (Aicha Benali - Enseignant - Chimie)\n";
echo " - amine.flutter@lms.com / Amine@2026 (Amine Haddad - Enseignant - Flutter)\n";
echo " - etudiant@lms.com / Karim@2024 (Karim Abadi - L3)\n";
echo " - sofia@lms.com / Sofia@2024 (Sofia Ghezzi - M1)\n";
echo " - ahmed.mourad@lms.com / Ahmed@2024 (Ahmed Mourad - L2)\n";
echo " - yasmine@lms.com / Yasmine@2024 (Yasmine Benkaoui - L3)\n";
echo " - mehdi@lms.com / Mehdi@2024 (Mehdi Rachid - M2)\n";
echo " - lina@lms.com / Lina@2024 (Lina Belkaid - L1)\n";
echo "\nCours créés:\n";
echo " - Introduction au Laravel (Fatima Zahra)\n";
echo " - Algorithmes et Structures de Données (Nicolas Lemoine)\n";
echo " - Développement Web (Fatima Zahra)\n";
echo " - Bases de Données (Nicolas Lemoine)\n";
echo " - Mécanique Classique (Mohamed Cherif)\n";
echo " - Chimie Organique (Aicha Benali)\n";
echo " - Développement Flutter (Amine Haddad)\n";

echo "Done.\n";
