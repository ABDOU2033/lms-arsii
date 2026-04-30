<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

try {
    $quiz = \App\Models\Quiz::where('titre', 'Quiz CSS3 Avancé')->first();
    
    if (!$quiz) {
        echo "❌ Quiz NOT FOUND\n";
        exit(1);
    }
    
    echo "✅ Quiz: " . $quiz->titre . "\n";
    echo "✅ Quiz ID: " . $quiz->id . "\n";
    echo "✅ Cours ID: " . $quiz->cours_id . "\n";
    
    $questions = $quiz->questions()->get();
    echo "\n📋 Questions count: " . $questions->count() . "\n";
    
    if ($questions->count() === 0) {
        echo "❌ NO QUESTIONS FOUND!\n";
        echo "\nDEBUG - All questions in database:\n";
        $allQuestions = \App\Models\Question::all();
        echo "Total questions: " . $allQuestions->count() . "\n";
        foreach ($allQuestions as $q) {
            echo "  Q" . $q->id . " - Quiz: " . $q->quiz_id . " - " . $q->enonce . "\n";
        }
    } else {
        echo "\n✅ Questions:\n";
        foreach ($questions as $q) {
            echo "  - " . $q->enonce . " (Quiz ID: " . $q->quiz_id . ")\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
