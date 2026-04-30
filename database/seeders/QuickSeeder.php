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
use Illuminate\Support\Facades\Hash;

class QuickSeeder extends Seeder
{
    public function run(): void
    {
        // Clear
        foreach ([User::class, Etudiant::class, Enseignant::class, Cours::class, Lecon::class, Quiz::class, Question::class, ChoixReponse::class, Inscription::class, Progression::class] as $model) {
            $model::truncate();
        }

        // Admin
        $admin = User::create([
            'nom' => 'Admin',
            'email' => 'admin@lms.com',
            'mot_de_passe' => Hash::make('Admin@1234'),
            'role' => 'administrateur',
            'actif' => true,
        ]);

        // Enseignant
        $ens = User::create([
            'nom' => 'Teacher',
            'email' => 'teacher@lms.com',
            'mot_de_passe' => Hash::make('Teacher@2024'),
            'role' => 'enseignant',
            'actif' => true,
        ]);
        $ens_profile = Enseignant::create([
            'user_id' => $ens->id,
            'specialite' => 'Web',
        ]);

        // Étudiant
        $stu = User::create([
            'nom' => 'Student',
            'email' => 'etudiant@lms.com',
            'mot_de_passe' => Hash::make('Karim@2024'),
            'role' => 'etudiant',
            'actif' => true,
        ]);
        $stu_profile = Etudiant::create([
            'user_id' => $stu->id,
            'niveau' => 'L3',
        ]);

        // Cours
        $cours = Cours::create([
            'titre' => 'HTML5 et CSS3',
            'description' => 'Web Development',
            'enseignant_id' => $ens_profile->id,
            'statut' => 'publie',
        ]);

        // Leçons
        Lecon::create([
            'cours_id' => $cours->id,
            'titre' => 'Lesson 1',
            'contenu' => 'Content 1',
            'type' => 'texte',
            'ordre' => 1,
        ]);

        // Quiz
        $quiz = Quiz::create([
            'cours_id' => $cours->id,
            'titre' => 'Quiz CSS3 Avancé',
            'duree' => 20,
            'note_max' => 15,
        ]);

        // Questions
        $q1 = Question::create([
            'quiz_id' => $quiz->id,
            'enonce' => 'Flexbox est pour les layouts 1D',
            'type' => 'vrai_faux',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q1->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q1->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        $q2 = Question::create([
            'quiz_id' => $quiz->id,
            'enonce' => 'CSS Grid est pour les layouts 2D',
            'type' => 'vrai_faux',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q2->id, 'contenu' => 'Vrai', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q2->id, 'contenu' => 'Faux', 'est_correcte' => false]);

        $q3 = Question::create([
            'quiz_id' => $quiz->id,
            'enonce' => 'Quelle propriété centre un élément avec Flexbox ?',
            'type' => 'qcm',
            'points' => 5,
        ]);
        ChoixReponse::create(['question_id' => $q3->id, 'contenu' => 'justify-content et align-items', 'est_correcte' => true]);
        ChoixReponse::create(['question_id' => $q3->id, 'contenu' => 'center-all', 'est_correcte' => false]);
        ChoixReponse::create(['question_id' => $q3->id, 'contenu' => 'align-center', 'est_correcte' => false]);

        // Inscription
        Inscription::create(['etudiant_id' => $stu_profile->id, 'cours_id' => $cours->id]);
        Progression::create(['etudiant_id' => $stu_profile->id, 'cours_id' => $cours->id, 'pourcentage' => 0]);

        echo "✅ Quick seeding completed!\n";
    }
}
