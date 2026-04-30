<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Quiz;

$quizzes = Quiz::with('questions')->get();

echo "Checking all quizzes for mismatches...\n\n";

foreach ($quizzes as $quiz) {
    $totalPoints = $quiz->questions->sum('points');
    $noteMax = $quiz->note_max;
    
    if ($totalPoints !== $noteMax) {
        echo "❌ {$quiz->titre}\n";
        echo "   note_max={$noteMax}, total_points={$totalPoints}\n";
        echo "   Questions:\n";
        foreach ($quiz->questions as $q) {
            echo "      - {$q->enonce}: {$q->points} pts\n";
        }
        echo "\n";
    }
}

echo "Done!\n";
