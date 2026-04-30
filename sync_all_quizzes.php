<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Direct database update to fix all quiz note_max values
$updates = [
    "UPDATE quizzes SET note_max = (SELECT SUM(points) FROM questions WHERE quiz_id = quizzes.id) WHERE note_max != (SELECT SUM(points) FROM questions WHERE quiz_id = quizzes.id)"
];

echo "Applying permanent fix to all quizzes...\n";

foreach ($updates as $sql) {
    try {
        DB::statement($sql);
        echo "✅ Updated all quizzes to match question point totals\n";
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Verify
use App\Models\Quiz;
$mismatches = 0;
$quizzes = Quiz::with('questions')->get();
foreach ($quizzes as $quiz) {
    $totalPoints = $quiz->questions->sum('points');
    if ($quiz->note_max != $totalPoints) {
        echo "❌ Still mismatch: {$quiz->titre} - note_max={$quiz->note_max}, total={$totalPoints}\n";
        $mismatches++;
    }
}

if ($mismatches === 0) {
    echo "\n✅ All quizzes are now correctly synced!\n";
} else {
    echo "\n❌ {$mismatches} quiz(zes) still have mismatches\n";
}
