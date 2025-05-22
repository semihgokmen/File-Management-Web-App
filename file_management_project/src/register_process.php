<?php
require_once 'helpers.php';

$username = $_POST['username'];
$password = $_POST['password'];

$usersFile = 'users.json';
$users = readJsonFile($usersFile);

foreach ($users as $user) {
    if ($user['username'] === $username) {
        header("Location: register_form.php?error=exists");
        exit();
    }
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$users[] = [
    'username' => $username,
    'password' => $hashedPassword
];

writeJsonFile($usersFile, $users);

header("Location: login_form.php?registered=1");
exit();
?>