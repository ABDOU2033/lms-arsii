<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$quiz = App\Models\Quiz::find(5);
if (!$quiz) {
    echo 'Quiz 5 not found\n';
    exit(0);
}

$count = $quiz->questions()->count();

echo 'Quiz 5 found: '.$quiz->titre.'\n';
echo 'Questions count: '.$count.'\n';
foreach ($quiz->questions as $q) {
    echo ' - Q'.$q->id.' ('.$q->type.') '.$q->enonce.' -> '. $q->choixReponses()->count() . ' choix'."\n";
}
