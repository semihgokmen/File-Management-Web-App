<?php
if (!isset($_GET['username'])) {
    die("Geçersiz işlem.");
}
require_once 'helpers.php';

$username = $_GET['username'];
$usersFile = 'users.json';

$users = readJsonFile($usersFile);

$userFound = false;
foreach ($users as &$user) {
    if ($user['username'] === $username) {
        $userFound = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user['password'] = $newPassword;

            writeJsonFile($usersFile, $users);

            echo "Parolanız başarıyla güncellendi. <a href='login_form.php'>Giriş Yap</a>";
            exit();
        }
        break;
    }
}

if (!$userFound) {
    header("Location: forgot_password_form.php?error=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Parola Sıfırla</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
  <h2>Parola Sıfırla</h2>
  <form action="" method="POST">
    <input type="password" name="password" placeholder="Yeni Parola" required>
    <button type="submit">Parolayı Güncelle</button>
  </form>
</body>
</html>