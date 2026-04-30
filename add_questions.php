<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

$quiz2 = Quiz::find(2);

$q3 = Question::create([
    'quiz_id' => $quiz2->id,
    'question_text' => 'Qu\'est-ce qu\'une route en Laravel?',
    'type' => 'multiple_choice',
]);

Answer::create(['question_id' => $q3->id, 'answer_text' => 'Un chemin URL', 'is_correct' => true]);
Answer::create(['question_id' => $q3->id, 'answer_text' => 'Une classe', 'is_correct' => false]);

$q4 = Question::create([
    'quiz_id' => $quiz2->id,
    'question_text' => 'Quel fichier contient les routes?',
    'type' => 'multiple_choice',
]);

Answer::create(['question_id' => $q4->id, 'answer_text' => 'routes/web.php', 'is_correct' => true]);
Answer::create(['question_id' => $q4->id, 'answer_text' => 'app/Routes.php', 'is_correct' => false]);

echo "✅ 2 questions ajoutées avec succès!\n";
