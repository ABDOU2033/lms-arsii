<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Progression;
use App\Models\User;

// Find Karim
$karim = User::where('nom', 'like', '%karim%')->orWhere('email', 'like', '%karim%')->first();
if (!$karim) {
    echo "Karim not found\n";
    // Try to find any etudiant
    $etudiants = User::where('role', 'etudiant')->get();
    echo "Available students:\n";
    foreach ($etudiants as $e) {
        echo "  - {$e->nom} {$e->prenom} (id: {$e->id})\n";
    }
    if ($etudiants->count() > 0) {
        $karim = $etudiants->first();
        echo "Using first student: {$karim->nom}\n";
    }
}

if ($karim) {
    $etudiant = $karim->etudiant;
    if ($etudiant) {
        $progressions = Progression::where('etudiant_id', $etudiant->id)->with('cours')->get();
        echo "Student: {$karim->nom} {$karim->prenom}\n";
        echo "Number of progressions: " . $progressions->count() . "\n\n";
        
        foreach ($progressions as $p) {
            $coursTitre = $p->cours ? $p->cours->titre : 'N/A';
            $totalLecons = $p->cours ? $p->cours->lecons()->count() : 0;
            $totalQuizzes = $p->cours ? $p->cours->quizzes()->count() : 0;
            $leconsVues = \App\Models\LeconVue::where('etudiant_id', $etudiant->id)
                ->where('cours_id', $p->cours_id)->count();
            
            echo "Course: {$coursTitre}\n";
            echo "  Progression: {$p->pourcentage}%\n";
            echo "  Lecons: {$leconsVues}/{$totalLecons}\n";
            echo "  Quiz: {$totalQuizzes} total\n";
            echo "\n";
        }
        
        $avg = $progressions->avg('pourcentage');
        echo "=== AVERAGE: {$avg}% ===\n";
    } else {
        echo "No etudiant record for this user\n";
    }
}
