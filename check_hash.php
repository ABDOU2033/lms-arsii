<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'ggg@lms.com')->first();
if ($user) {
    echo "Exists.\n";
    echo "mot_de_passe in DB: " . $user->mot_de_passe . "\n";
    if (\Illuminate\Support\Facades\Hash::check('GGG11111', $user->mot_de_passe)) {
        echo "Hash Matches GGG11111\n";
    } else {
        echo "Hash DOES NOT MATCH GGG11111\n";
    }
} else {
    echo "Not found.\n";
}
