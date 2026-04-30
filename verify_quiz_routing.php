<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Quiz;

$quiz = Quiz::where('titre', 'Quiz 3: Routing et Contrôleurs')->first();
if ($quiz) {
    $totalPoints = $quiz->questions()->sum('points');
    echo "Quiz 3: Routing et Contrôleurs\n";
    echo "  DB note_max: {$quiz->note_max}\n";
    echo "  Total question points: {$totalPoints}\n";
    echo "  Match: " . ($quiz->note_max === $totalPoints ? "YES ✅" : "NO ❌") . "\n";
} else {
    echo "Quiz not found\n";
}
