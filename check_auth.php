<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$email = 'admin@lms.test';
$password = 'password123';

$user = User::where('email', $email)->first();
if (!$user) {
    echo "USER_NOT_FOUND\n";
    exit(0);
}

echo "User found: id={$user->id}, email={$user->email}, password_hash={$user->password}\n";

$attempt = Auth::attempt(['email' => $email, 'password' => $password]);

if ($attempt) {
    echo "AUTH_SUCCESS\n";
} else {
    echo "AUTH_FAILED\n";
    // Verify password manually
    if (password_verify($password, $user->password)) {
        echo "password_verify: OK\n";
    } else {
        echo "password_verify: FAILED\n";
    }
}
