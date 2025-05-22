<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
  <h2>Giriş Yap</h2>
  <br>
  <form action="login_process.php" method="POST">
    <input type="text" name="username" placeholder="Kullanıcı Adı" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Giriş</button>
    <a href="register_form.php">Üye değil misin? Kayıt ol</a>
    <a href="forgot_password_form.php">Şifremi unuttum</a>
  </form>
  <br> 
  <?php if (isset($_GET['error'])): ?>
    <p>Kullanıcı adı veya şifre hatalı.</p>
  <?php endif; ?>
</body>
</html>