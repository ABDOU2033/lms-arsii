<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Cours;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\ChoixReponse;
use App\Models\Inscription;
use App\Models\Progression;
use App\Models\Reponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear all tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        Etudiant::truncate();
        Enseignant::truncate();
        Cours::truncate();
        Lecon::truncate();
        Quiz::truncate();
        Question::truncate();
        ChoixReponse::truncate();
        Inscription::truncate();
        Progression::truncate();
        Reponse::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ===== ADMIN =====
        $admin = User::create([
            'nom' => 'Admin Ahmed',
            'email' => 'admin@lms.com',
            'mot_de_passe' => Hash::make('Admin@1234'),
            'role' => 'administrateur',
            'actif' => true,
        ]);

        // ===== ENSEIGNANTS =====
        $ens1 = User::create([
            'nom' => 'Fatima Zahra',
            'email' => 'fatima@lms.com',
            'mot_de_passe' => Hash::make('Fatima@2024'),
            'role' => 'enseignant',
            'actif' => true,
        ]);
        $ens1_profile = Enseignant::create([
            'user_id' => $ens1->id,
            'specialite' => 'Backend Laravel',
            'bio' => 'Experte en développement PHP/Laravel',
        ]);

        $ens2 = User::create([
            'nom' => 'Nicolas Lemoine',
            'email' => 'nicolas@lms.com',
            'mot_de_passe' => Hash::make('Nicolas@2024'),
            'role' => 'enseignant',
            'actif' => true,
        ]);
        $ens2_profile = Enseignant::create([
            'user_id' => $ens2->id,
            'specialite' => 'Frontend Web',
            'bio' => 'Expert HTML, CSS, JavaScript',
        ]);

        // ===== ETUDIANTS =====
        $stu1 = User::create([
            'nom' => 'Karim Abadi',
            'email' => 'etudiant@lms.com',
            'mot_de_passe' => Hash::make('Karim@2024'),
            'role' => 'etudiant',
            'actif' => true,
        ]);
        $stu1_profile = Etudiant::create([
            'user_id' => $stu1->id,
            'niveau' => 'Licence 3',
        ]);

        $stu2 = User::create([
            'nom' => 'Sofia Ghezzi',
            'email' => 'sofia@lms.com',
            'mot_de_passe' => Hash::make('Sofia@2024'),
            'role' => 'etudiant',
            'actif' => true,
        ]);
        $stu2_profile = Etudiant::create([
            'user_id' => $stu2->id,
            'niveau' => 'Master 1',
        ]);

        // ===== COURS 1 =====
        $cours1 = Cours::create([
            'titre' => 'Laravel 11 - Backend Avancé',
            'description' => 'Maîtrisez Laravel 11 pour créer des applications web robustes',
            'enseignant_id' => $ens1_profile->id,
            'statut' => 'publie',
        ]);

        // Leçons du Cours 1
        Lecon::create([
            'cours_id' => $cours1->id,
            'titre' => 'Installation et Configuration',
            'contenu' => 'Installez Laravel 11 via Composer et configurez votre environnement',
            'type' => 'texte',
            'ordre' => 1,
        ]);

        Lecon::create([
            'cours_id' => $cours1->id,
            'titre' => 'Eloquent ORM',
            'contenu' => 'Utilisez Eloquent pour gérer votre base de données avec élégance',
            'type' => 'texte',
            'ordre' => 2,
        ]);

        Lecon::create([
            'cours_id' => $cours1->id,
            'titre' => 'Migrations et Seeders',
            'contenu' => 'Gérez vos schémas de base de données avec les migrations',
            'type' => 'texte',
            'ordre' => 3,
        ]);

        Lecon::create([
            'cours_id' => $cours1->id,
            'titre' => 'Authentification',
            'contenu' => 'Implémentez l\'authentification sécurisée avec Laravel Sanctum',
            'type' => 'texte',
            'ordre' => 4,
        ]);

        // Quiz 1 du Cours 1
        $quiz1 = Quiz::create([
            'cours_id' => $cours1->id,
            'titre' => 'Quiz Laravel Fondamentaux',
            'duree' => 20,
            'note_max' => 10,
        ]);

        $q1 = Question::create([
            'quiz_id' => $quiz1->id,
            'enonce' => 'Quelle commande crée un nouveau projet Laravel ?',
            'type' => 'qcm',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q1->id, 'contenu' => 'composer create-project laravel/laravel', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q1->id, 'contenu' => 'php artisan new', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q1->id, 'contenu' => 'laravel new project', 'est_correcte' => false]);

        $q2 = Question::create([
            'quiz_id' => $quiz1->id,
            'enonce' => 'Laravel utilise l\'architecture MVC',
            'type' => 'vrai_faux',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q2->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q2->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        // Quiz 2 du Cours 1
        $quiz1b = Quiz::create([
            'cours_id' => $cours1->id,
            'titre' => 'Quiz Eloquent ORM',
            'duree' => 25,
            'note_max' => 15,
        ]);

        $q1b = Question::create([
            'quiz_id' => $quiz1b->id,
            'enonce' => 'Comment récupérer tous les utilisateurs avec Eloquent ?',
            'type' => 'qcm',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q1b->id, 'contenu' => 'User::all()', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q1b->id, 'contenu' => 'User::find()', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q1b->id, 'contenu' => 'User::get()', 'est_correcte' => false]);

        $q2b = Question::create([
            'quiz_id' => $quiz1b->id,
            'enonce' => 'Les migrations permettent de versionner le schéma de base de données',
            'type' => 'vrai_faux',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q2b->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q2b->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        $q3b = Question::create([
            'quiz_id' => $quiz1b->id,
            'enonce' => 'Quelle est la commande pour créer une migration ?',
            'type' => 'qcm',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q3b->id, 'contenu' => 'php artisan make:migration', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q3b->id, 'contenu' => 'php artisan migration:create', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q3b->id, 'contenu' => 'php artisan create:migration', 'est_correcte' => false]);

        Inscription::create(['etudiant_id' => $stu1_profile->id, 'cours_id' => $cours1->id]);
        Inscription::create(['etudiant_id' => $stu2_profile->id, 'cours_id' => $cours1->id]);
        Progression::create(['etudiant_id' => $stu1_profile->id, 'cours_id' => $cours1->id, 'pourcentage' => 50]);
        Progression::create(['etudiant_id' => $stu2_profile->id, 'cours_id' => $cours1->id, 'pourcentage' => 30]);

        // ===== COURS 2 =====
        $cours2 = Cours::create([
            'titre' => 'HTML5 et CSS3',
            'description' => 'Apprenez HTML5 et CSS3 modernes pour le développement web',
            'enseignant_id' => $ens2_profile->id,
            'statut' => 'publie',
        ]);

        // Leçons du Cours 2
        Lecon::create([
            'cours_id' => $cours2->id,
            'titre' => 'HTML5 Fondamentaux',
            'contenu' => 'Découvrez les balises HTML5 sémantiques et leur utilisation',
            'type' => 'texte',
            'ordre' => 1,
        ]);

        Lecon::create([
            'cours_id' => $cours2->id,
            'titre' => 'CSS3 et Sélecteurs',
            'contenu' => 'Maîtrisez les sélecteurs CSS3 avancés',
            'type' => 'texte',
            'ordre' => 2,
        ]);

        Lecon::create([
            'cours_id' => $cours2->id,
            'titre' => 'Flexbox',
            'contenu' => 'Créez des layouts responsifs avec Flexbox',
            'type' => 'texte',
            'ordre' => 3,
        ]);

        Lecon::create([
            'cours_id' => $cours2->id,
            'titre' => 'CSS Grid',
            'contenu' => 'Maîtrisez CSS Grid pour les layouts complexes',
            'type' => 'texte',
            'ordre' => 4,
        ]);

        // Quiz 1 du Cours 2
        $quiz2 = Quiz::create([
            'cours_id' => $cours2->id,
            'titre' => 'Quiz HTML5 Fondamentaux',
            'duree' => 15,
            'note_max' => 12,
        ]);

        $q3 = Question::create([
            'quiz_id' => $quiz2->id,
            'enonce' => 'Quel élément crée un paragraphe en HTML ?',
            'type' => 'qcm',
            'points' => 4,
        ]);
        ChoixReponse::create(['question_id' => $q3->id, 'contenu' => '<p>', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q3->id, 'contenu' => '<div>', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q3->id, 'contenu' => '<span>', 'est_correcte' => false]);

        $q4 = Question::create([
            'quiz_id' => $quiz2->id,
            'enonce' => 'HTML5 est une évolution de HTML4',
            'type' => 'vrai_faux',
            'points' => 4,
        ]);
        ChoixReponse::create(['question_id' => $q4->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q4->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        $q5 = Question::create([
            'quiz_id' => $quiz2->id,
            'enonce' => 'Quel est l\'attribut pour un lien hypertexte ?',
            'type' => 'qcm',
            'points' => 4,
        ]);
        ChoixReponse::create(['question_id' => $q5->id, 'contenu' => 'href', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q5->id, 'contenu' => 'link', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q5->id, 'contenu' => 'url', 'est_correcte' => false]);

        // Quiz 2 du Cours 2
        $quiz2b = Quiz::create([
            'cours_id' => $cours2->id,
            'titre' => 'Quiz CSS3 Avancé',
            'duree' => 20,
            'note_max' => 15,
        ]);

        $q6 = Question::create([
            'quiz_id' => $quiz2b->id,
            'enonce' => 'Flexbox est pour les layouts 1D',
            'type' => 'vrai_faux',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q6->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q6->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        $q7 = Question::create([
            'quiz_id' => $quiz2b->id,
            'enonce' => 'CSS Grid est pour les layouts 2D',
            'type' => 'vrai_faux',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q7->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q7->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        $q8 = Question::create([
            'quiz_id' => $quiz2b->id,
            'enonce' => 'Quelle propriété centre un élément avec Flexbox ?',
            'type' => 'qcm',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q8->id, 'contenu' => 'justify-content et align-items', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q8->id, 'contenu' => 'center-all', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q8->id, 'contenu' => 'align-center', 'est_correcte' => false]);

        Inscription::create(['etudiant_id' => $stu1_profile->id, 'cours_id' => $cours2->id]);
        Inscription::create(['etudiant_id' => $stu2_profile->id, 'cours_id' => $cours2->id]);
        Progression::create(['etudiant_id' => $stu1_profile->id, 'cours_id' => $cours2->id, 'pourcentage' => 75]);
        Progression::create(['etudiant_id' => $stu2_profile->id, 'cours_id' => $cours2->id, 'pourcentage' => 60]);

        // ===== RÉPONSES AUX QUIZ - COURS 1 =====
        // Quiz 1 Fondamentaux - Étudiant 1 (10/10)
        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q1->id,
            'contenu' => 'composer create-project laravel/laravel',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q2->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        // Quiz 1 Fondamentaux - Étudiant 2 (5/10)
        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q1->id,
            'contenu' => 'php artisan new',
            'est_correcte' => false,
            'score_obtenu' => 0,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q2->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        // Quiz 2 Eloquent - Étudiant 1 (15/15)
        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q1b->id,
            'contenu' => 'User::all()',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q2b->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q3b->id,
            'contenu' => 'php artisan make:migration',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        // Quiz 2 Eloquent - Étudiant 2 (10/15)
        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q1b->id,
            'contenu' => 'User::find()',
            'est_correcte' => false,
            'score_obtenu' => 0,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q2b->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q3b->id,
            'contenu' => 'php artisan migration:create',
            'est_correcte' => false,
            'score_obtenu' => 0,
        ]);

        // ===== RÉPONSES AUX QUIZ - COURS 2 =====
        // Quiz 1 HTML5 - Étudiant 1 (12/12)
        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q3->id,
            'contenu' => '<p>',
            'est_correcte' => true,
            'score_obtenu' => 4,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q4->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 4,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q5->id,
            'contenu' => 'href',
            'est_correcte' => true,
            'score_obtenu' => 4,
        ]);

        // Quiz 1 HTML5 - Étudiant 2 (8/12)
        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q3->id,
            'contenu' => '<div>',
            'est_correcte' => false,
            'score_obtenu' => 0,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q4->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 4,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q5->id,
            'contenu' => 'href',
            'est_correcte' => true,
            'score_obtenu' => 4,
        ]);

        // Quiz 2 CSS3 - Étudiant 1 (15/15)
        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q6->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q7->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu1_profile->id,
            'question_id' => $q8->id,
            'contenu' => 'justify-content et align-items',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        // Quiz 2 CSS3 - Étudiant 2 (10/15)
        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q6->id,
            'contenu' => 'Vrai',
            'est_correcte' => true,
            'score_obtenu' => 5,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q7->id,
            'contenu' => 'Faux',
            'est_correcte' => false,
            'score_obtenu' => 0,
        ]);

        Reponse::create([
            'etudiant_id' => $stu2_profile->id,
            'question_id' => $q8->id,
            'contenu' => 'center-all',
            'est_correcte' => false,
            'score_obtenu' => 0,
        ]);
    }
}
