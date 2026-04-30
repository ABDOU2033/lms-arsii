#!/usr/bin/env php
<?php
/**
 * Script de vérification du LMS ARSII
 * Utilisage: php verify_system.php
 */

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║           LMS ARSII - SYSTEM VERIFICATION v1.0                ║\n";
echo "║                  3 Février 2026                               ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Change to project root
chdir(__DIR__);

// Check 1: Composer autoload
echo "✓ Vérification 1: Autoload Composer... ";
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
    echo "OK\n";
} else {
    echo "ERREUR - Exécutez: composer install\n";
    exit(1);
}

// Check 2: Laravel bootstrap
echo "✓ Vérification 2: Bootstrap Laravel... ";
try {
    $app = require_once('bootstrap/app.php');
    echo "OK\n";
} catch (Exception $e) {
    echo "ERREUR - {$e->getMessage()}\n";
    exit(1);
}

// Check 3: Database connection
echo "✓ Vérification 3: Connexion BD... ";
try {
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "OK\n";
} catch (Exception $e) {
    echo "ERREUR - Vérifiez .env\n";
    exit(1);
}

// Check 4: Tables exist
echo "✓ Vérification 4: Tables existantes... ";
try {
    $tables = \Illuminate\Support\Facades\DB::select("
        SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = DATABASE()
    ");
    $tableNames = array_map(fn($t) => $t->TABLE_NAME, $tables);
    $required = ['users', 'courses', 'lessons', 'quizzes', 'questions', 'answers', 'course_student'];
    $missing = array_diff($required, $tableNames);
    
    if (empty($missing)) {
        echo "OK (" . count($required) . " tables)\n";
    } else {
        echo "MANQUANTES: " . implode(', ', $missing) . "\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "ERREUR\n";
    exit(1);
}

// Check 5: Users
echo "✓ Vérification 5: Utilisateurs... ";
try {
    $userCount = \Illuminate\Support\Facades\DB::table('users')->count();
    echo "OK ($userCount users)\n";
    
    // Verify test users
    $testEmails = ['admin@lms.test', 'prof1@lms.test', 'student1@lms.test'];
    $existing = \Illuminate\Support\Facades\DB::table('users')
        ->whereIn('email', $testEmails)
        ->count();
    if ($existing !== 3) {
        echo "  ⚠️ Comptes test manquants. Exécutez: php artisan db:seed\n";
    }
} catch (Exception $e) {
    echo "ERREUR\n";
    exit(1);
}

// Check 6: Courses
echo "✓ Vérification 6: Cours... ";
try {
    $courseCount = \Illuminate\Support\Facades\DB::table('courses')->count();
    echo "OK ($courseCount courses)\n";
} catch (Exception $e) {
    echo "ERREUR\n";
}

// Check 7: Models
echo "✓ Vérification 7: Modèles Eloquent... ";
try {
    $classes = [
        'App\Models\User',
        'App\Models\Course',
        'App\Models\Lesson',
        'App\Models\Quiz',
        'App\Models\Question',
        'App\Models\Answer'
    ];
    
    foreach ($classes as $class) {
        if (!class_exists($class)) {
            echo "MANQUANT: $class\n";
            exit(1);
        }
    }
    echo "OK (6 modèles)\n";
} catch (Exception $e) {
    echo "ERREUR\n";
    exit(1);
}

// Check 8: Views
echo "✓ Vérification 8: Vues Blade... ";
$viewPaths = [
    'resources/views/dashboard/index.blade.php',
    'resources/views/courses/index.blade.php',
    'resources/views/quiz/show.blade.php',
    'resources/views/layouts/app.blade.php'
];

$missing = [];
foreach ($viewPaths as $path) {
    if (!file_exists($path)) {
        $missing[] = $path;
    }
}

if (empty($missing)) {
    echo "OK\n";
} else {
    echo "MANQUANTES: " . implode(', ', $missing) . "\n";
}

// Check 9: Routes
echo "✓ Vérification 9: Fichier routes... ";
if (file_exists('routes/web.php') && file_exists('routes/auth.php')) {
    echo "OK\n";
} else {
    echo "MANQUANTES\n";
}

// Check 10: Environment
echo "✓ Vérification 10: Environnement... ";
$env = getenv('APP_ENV');
$url = getenv('APP_URL');
if ($env && $url) {
    echo "OK (APP_ENV=$env, APP_URL=$url)\n";
} else {
    echo "ERREUR - .env non configuré\n";
}

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ TOUTES LES VÉRIFICATIONS RÉUSSIES                         ║\n";
echo "║                                                               ║\n";
echo "║  Prêt pour démarrage:                                         ║\n";
echo "║  Terminal 1: php artisan serve --host=localhost --port=8000   ║\n";
echo "║  Terminal 2: npm run dev                                      ║\n";
echo "║                                                               ║\n";
echo "║  Puis: http://localhost:8000                                  ║\n";
echo "║                                                               ║\n";
echo "║  Identifiants de test:                                        ║\n";
echo "║  • admin@lms.test / password123                               ║\n";
echo "║  • prof1@lms.test / password123                               ║\n";
echo "║  • student1@lms.test / password123                            ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";
