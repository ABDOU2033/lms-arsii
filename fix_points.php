<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Question::where('enonce', 'like', '%animations%')->update(['points' => 4]);
\App\Models\Question::where('enonce', 'like', '%closures%')->update(['points' => 3]);
\App\Models\Question::where('enonce', 'like', '%convertir une chaîne en nombre%')->update(['points' => 4]);
\App\Models\Question::where('enonce', 'like', '%box model%')->update(['points' => 3]);
\App\Models\Question::where('enonce', 'like', '%pseudo-classe CSS%')->update(['points' => 6]);

\App\Models\Question::where('points', 1)->get()->groupBy('quiz_id')->each(function($questions, $quiz_id) { 
    $quiz = \App\Models\Quiz::find($quiz_id); 
    if ($quiz && $questions->count() > 0) { 
        $pts = floor($quiz->note_max / $questions->count()); 
        if($pts < 1) $pts = 1; 
        $questions->each(function($q) use ($pts) { 
            $q->update(['points' => $pts]); 
        }); 
    } 
});

echo "Done\n";
