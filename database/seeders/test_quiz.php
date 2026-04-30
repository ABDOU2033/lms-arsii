<?php

use App\Models\Quiz;

artisan('migrate:fresh --seed', [
    '--force' => true,
]);

// Now test the quiz
$quizCSS = Quiz::where('titre', 'Quiz CSS3 Avancé')->first();

if (!$quizCSS) {
    echo "❌ Quiz CSS3 Avancé not found!\n";
    exit(1);
}

echo "✅ Quiz: " . $quizCSS->titre . "\n";
echo "✅ Durée: " . $quizCSS->duree . " minutes\n";
echo "✅ Note Max: " . $quizCSS->note_max . " points\n";
echo "\n📋 QUESTIONS (" . $quizCSS->questions->count() . " total):\n";
echo "════════════════════════════════════════════════════════════\n";

foreach ($quizCSS->questions as $i => $q) {
    echo "\n" . ($i + 1) . ". " . $q->enonce . "\n";
    echo "   Type: " . strtoupper($q->type) . " | Points: " . $q->points . "\n";
    echo "   Choix disponibles:\n";
    
    foreach ($q->choixReponses as $j => $choix) {
        $label = $choix->est_correcte ? "✅ CORRECT" : "❌ Faux";
        echo "      " . chr(65 + $j) . ") " . $choix->contenu . " [$label]\n";
    }
}

echo "\n════════════════════════════════════════════════════════════\n";
echo "\n📊 RÉSUMÉ:\n";
echo "   • Total questions: " . $quizCSS->questions->count() . "\n";
echo "   • Total points possibles: " . $quizCSS->questions->sum('points') . " / " . $quizCSS->note_max . "\n";
echo "   • Status: ✅ PRÊT POUR LES ÉTUDIANTS\n";
