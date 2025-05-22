<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$usersFile = 'users.json';

// Dosya varsa oku
if (!file_exists($usersFile)) {
    header("Location: login_form.php?error=1");
    exit();
}

$users = json_decode(file_get_contents($usersFile), true);

foreach ($users as $user) {
    // Kullanıcı adı eşleşiyorsa ve şifre doğruysa
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // giriş sonrası yönlendirme
        exit();
    }
}

// Giriş başarısızsa tekrar form sayfasına yönlendir
header("Location: login_form.php?error=1");
exit();
?>