<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Vérifier les derniers utilisateurs créés
$users = DB::table('users')->orderBy('id', 'desc')->limit(5)->get();

echo "📋 Derniers utilisateurs créés:\n";
echo str_repeat("-", 80) . "\n";

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Nom: {$user->nom}\n";
    echo "Email: {$user->email}\n";
    echo "Rôle: {$user->role}\n";
    echo "Actif: " . ($user->actif ? 'OUI ✓' : 'NON ✗') . "\n";
    echo "Mot de passe (hash): " . substr($user->mot_de_passe, 0, 30) . "...\n";
    echo str_repeat("-", 80) . "\n";
}

// Tester un mot de passe
echo "\n🧪 Test de mot de passe:\n";
$email = readline("Entrez l'email à tester: ");
$password = readline("Entrez le mot de passe à tester: ");

$user = DB::table('users')->where('email', $email)->first();

if ($user) {
    echo "\n✅ Utilisateur trouvé!\n";
    echo "Actif: " . ($user->actif ? 'OUI ✓' : 'NON ✗') . "\n";
    
    $check = Hash::check($password, $user->mot_de_passe);
    echo "Mot de passe correct: " . ($check ? 'OUI ✓' : 'NON ✗') . "\n";
    
    if ($check && $user->actif) {
        echo "\n🎉 Cet utilisateur devrait pouvoir se connecter!\n";
    } else {
        echo "\n❌ Problème détecté:\n";
        if (!$user->actif) {
            echo "  - Le compte n'est pas actif\n";
        }
        if (!$check) {
            echo "  - Le mot de passe est incorrect\n";
        }
    }
} else {
    echo "\n❌ Utilisateur non trouvé!\n";
}
