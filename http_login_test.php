<?php
// Simule GET /login puis POST /login en respectant cookies et CSRF
$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/laravel_test_cookies.txt';

function curl_get($url, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return [$res, $info];
}

function curl_post($url, $postFields, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return [$res, $info];
}

list($loginHtml, $info) = curl_get($base . '/login', $cookieFile);
if ($loginHtml === false) { echo "GET /login failed\n"; exit(1); }

// find csrf token
if (preg_match('/name="_token"\s+value="([^"]+)"/', $loginHtml, $m)) {
    $token = $m[1];
    echo "CSRF token found: $token\n";
} else {
    echo "CSRF token NOT found in /login HTML\n";
    // try to find meta
    if (preg_match('/meta name="csrf-token" content="([^"]+)"/', $loginHtml, $m2)) {
        $token = $m2[1];
        echo "Meta CSRF token found: $token\n";
    } else {
        echo "No CSRF token at all.\n";
        $token = null;
    }
}

// Prepare credentials
$email = 'admin@lms.test';
$password = 'password123';
$post = [
    '_token' => $token,
    'email' => $email,
    'password' => $password,
    'remember' => '0'
];

list($postRes, $postInfo) = curl_post($base . '/login', $post, $cookieFile);

echo "POST /login HTTP status: " . ($postInfo['http_code'] ?? 'n/a') . "\n";
// if redirected, show final url
if (!empty($postInfo['redirect_url'])) {
    echo "Redirected to: " . $postInfo['redirect_url'] . "\n";
}

// show whether response contains error message
if (strpos($postRes, 'These credentials do not match our records') !== false || strpos($postRes, 'These credentials') !== false) {
    echo "Server returned credential error in response body.\n";
} else {
    echo "No credential error string found in response body.\n";
}

// show some cookie contents
if (file_exists($cookieFile)) {
    echo "--- Cookies file contents ---\n";
    echo file_get_contents($cookieFile);
} else {
    echo "No cookie file created.\n";
}

// Print a short snippet of response
echo "--- Response snippet ---\n";
echo substr($postRes, 0, 1000);

?>