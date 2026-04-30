<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Quiz;

$fixes = [
    'Quiz 2: Complexité' => 5,
    'Quiz 3: Structures de Données' => 15,
    'Quiz 3: Routing et Contrôleurs' => 20,
    'Quiz SQL Fondamental' => 20,
    'Quiz Avancé SQL' => 25,
];

echo "Fixing quizzes...\n\n";

foreach ($fixes as $title => $correctNoteMax) {
    $quiz = Quiz::where('titre', $title)->first();
    if ($quiz) {
        $oldNoteMax = $quiz->note_max;
        $quiz->update(['note_max' => $correctNoteMax]);
        echo "✅ {$title}: note_max {$oldNoteMax} → {$correctNoteMax}\n";
    } else {
        echo "❌ {$title}: Not found\n";
    }
}

echo "\nDone!\n";
