<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Enseignant;
use App\Models\Etudiant;

echo "🔍 Vérification des utilisateurs...\n\n";

// 1. Afficher tous les utilisateurs
$users = DB::table('users')->orderBy('id', 'desc')->limit(10)->get();

echo "📋 Derniers 10 utilisateurs:\n";
echo str_repeat("-", 90) . "\n";
printf("%-4s | %-20s | %-30s | %-15s | %-6s\n", "ID", "Nom", "Email", "Role", "Actif");
echo str_repeat("-", 90) . "\n";

foreach ($users as $user) {
    $actif = $user->actif ? '✓' : '✗';
    printf("%-4s | %-20s | %-30s | %-15s | %-6s\n", 
           $user->id, 
           substr($user->nom, 0, 20), 
           substr($user->email, 0, 30), 
           $user->role, 
           $actif);
}

echo str_repeat("-", 90) . "\n";

// 2. Activer TOUS les utilisateurs qui ne le sont pas
$inactiveUsers = DB::table('users')->where('actif', 0)->get();

if ($inactiveUsers->count() > 0) {
    echo "\n⚠️  {$inactiveUsers->count()} utilisateur(s) inactif(s) détecté(s)\n";
    echo "🔧 Activation en cours...\n\n";
    
    DB::table('users')->where('actif', 0)->update(['actif' => 1]);
    
    echo "✅ Tous les utilisateurs ont été activés!\n\n";
} else {
    echo "\n✅ Tous les utilisateurs sont déjà actifs.\n\n";
}

// 3. Vérifier les profils manquants
echo "🔍 Vérification des profils...\n\n";

$allUsers = User::all();
$fixed = 0;

foreach ($allUsers as $user) {
    if ($user->role === 'etudiant') {
        $etudiant = Etudiant::where('user_id', $user->id)->first();
        if (!$etudiant) {
            echo "⚠️  Étudiant manquant pour: {$user->email}\n";
            Etudiant::create(['user_id' => $user->id, 'niveau' => 'Non spécifié']);
            echo "✅ Profil étudiant créé\n";
            $fixed++;
        }
    } elseif ($user->role === 'enseignant') {
        $enseignant = Enseignant::where('user_id', $user->id)->first();
        if (!$enseignant) {
            echo "⚠️  Enseignant manquant pour: {$user->email}\n";
            Enseignant::create(['user_id' => $user->id, 'specialite' => 'Non spécifié', 'bio' => null]);
            echo "✅ Profil enseignant créé\n";
            $fixed++;
        }
    }
}

if ($fixed === 0) {
    echo "✅ Tous les profils existent!\n";
} else {
    echo "\n✅ {$fixed} profil(s) créé(s)!\n";
}

echo "\n🎉 Vérification terminée!\n";
echo "\nVous pouvez maintenant tester la connexion avec n'importe quel utilisateur.\n";
