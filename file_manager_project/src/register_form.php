<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>
    <h2>Kayıt Ol</h2>
    <br>

    <form action="register_process.php" method="POST">
        <input type="text" name="username" placeholder="Kullanıcı adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit">Kayıt Ol</button>
        <a href="login_form.php">Zaten üye misin? Giriş yap</a>
    </form>
    <br>
    <?php if (isset($_GET['error'])): ?>
        <p>Bu kullanıcı adı alınmış.</p>
    <?php endif; ?>
</body>
</html>