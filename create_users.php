<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;

echo "🔄 Suppression des utilisateurs existants...\n";
User::truncate();

echo "✅ Création des nouveaux utilisateurs...\n\n";

// Admin
$admin = User::create([
    'name' => 'Administrateur',
    'email' => 'admin@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'admin',
]);
echo "✅ Admin créé: admin@lms.test\n";

// Prof 1
$prof1 = User::create([
    'name' => 'Dr. Ahmed Karim',
    'email' => 'prof1@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'teacher',
]);
echo "✅ Prof 1 créé: prof1@lms.test\n";

// Prof 2
$prof2 = User::create([
    'name' => 'Mme Fatima Saïd',
    'email' => 'prof2@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'teacher',
]);
echo "✅ Prof 2 créé: prof2@lms.test\n";

// Étudiant 1
$student1 = User::create([
    'name' => 'Ali Mohammed',
    'email' => 'student1@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'student',
]);
echo "✅ Étudiant 1 créé: student1@lms.test\n";

// Étudiant 2
$student2 = User::create([
    'name' => 'Leila Hassan',
    'email' => 'student2@lms.test',
    'password' => bcrypt('password123'),
    'role' => 'student',
]);
echo "✅ Étudiant 2 créé: student2@lms.test\n";

echo "\n";
echo "═══════════════════════════════════════════\n";
echo "✅ TOUS LES UTILISATEURS CRÉÉS!\n";
echo "═══════════════════════════════════════════\n\n";

echo "📝 IDENTIFIANTS POUR LA CONNEXION:\n";
echo "   Admin:     admin@lms.test\n";
echo "   Prof 1:    prof1@lms.test\n";
echo "   Prof 2:    prof2@lms.test\n";
echo "   Étudiant 1: student1@lms.test\n";
echo "   Étudiant 2: student2@lms.test\n\n";
echo "🔑 Mot de passe (pour tous): password123\n\n";

// Vérification
echo "🔍 Vérification:\n";
echo "Total d'utilisateurs: " . User::count() . "\n";
?>
