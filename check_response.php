<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$r = App\Models\Reponse::with('question')->orderBy('id','desc')->first();
if(!$r){
    echo "No response\n";
    exit;
}

echo 'Q='. $r->question->enonce ."\n";
echo 'Réponse utilisateur='. $r->contenu ."\n";
echo 'Est correcte=' . ($r->est_correcte ? 'oui' : 'non') ."\n";
echo 'Score obtenu=' . $r->score_obtenu ."\n";
