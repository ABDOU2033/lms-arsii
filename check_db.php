#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

try {
    // Run direct database test
    \DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Count records
    $quizzes = \DB::table('quizzes')->count();
    $questions = \DB::table('questions')->count();
    $choix = \DB::table('choix_reponses')->count();
    $reponses = \DB::table('reponses')->count();
    
    echo "📊 Database Records:\n";
    echo "   • Quizzes: $quizzes\n";
    echo "   • Questions: $questions\n";
    echo "   • Choix Réponses: $choix\n";
    echo "   • Réponses Étudiants: $reponses\n";
    
    // Get Quiz CSS3 Avancé details
    $quizCSS = \DB::table('quizzes')
        ->where('titre', 'Quiz CSS3 Avancé')
        ->first();
    
    if (!$quizCSS) {
        echo "\n⚠️  Quiz CSS3 Avancé not found yet\n";
    } else {
        echo "\n✅ Quiz CSS3 Avancé Found!\n";
        echo "   • ID: " . $quizCSS->id . "\n";
        echo "   • Durée: " . $quizCSS->duree . " minutes\n";
        echo "   • Note Max: " . $quizCSS->note_max . " points\n";
        
        $qCount = \DB::table('questions')->where('quiz_id', $quizCSS->id)->count();
        echo "   • Questions: $qCount\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
