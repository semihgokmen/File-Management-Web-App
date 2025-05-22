<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Şifremi Unuttum</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
  <h2>Şifremi Unuttum</h2>
  <form action="reset_password_process.php" method="GET">
    <input type="text" name="username" placeholder="Kullanıcı Adınız" required>
    <button type="submit">Devam Et</button>
  </form>
  <?php if (isset($_GET['error'])): ?>
    <p style="color: red;">Kullanıcı adı bulunamadı.</p>
  <?php endif; ?>
</body>
</html>