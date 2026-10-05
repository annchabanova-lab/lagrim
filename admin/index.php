<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (password_verify($_POST['password'] ?? '', ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Mot de passe incorrect';
}

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — La Grimouillière</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --creme: #F7F2E9;
    --sauge: #92A17F;
    --tuile: #B5622E;
    --taupe: #4A4038;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Poppins', sans-serif;
    background: var(--creme);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .login-card {
    background: white;
    border-radius: 12px;
    padding: 3rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 4px 20px rgba(74,64,56,0.08);
    text-align: center;
  }
  .login-card h1 {
    font-family: 'Playfair Display', serif;
    color: var(--taupe);
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
  }
  .login-card .subtitle {
    color: var(--sauge);
    font-size: 0.85rem;
    margin-bottom: 2rem;
  }
  .login-card input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    text-align: center;
  }
  .login-card input:focus {
    outline: none;
    border-color: var(--sauge);
  }
  .login-card button {
    width: 100%;
    padding: 12px;
    background: var(--tuile);
    color: white;
    border: none;
    border-radius: 6px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 1px;
  }
  .login-card button:hover { opacity: 0.9; }
  .error {
    color: var(--tuile);
    font-size: 0.85rem;
    margin-bottom: 1rem;
  }
</style>
</head>
<body>
<div class="login-card">
  <h1>La Grimouillière</h1>
  <p class="subtitle">Administration du site</p>
  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form method="POST">
    <input type="password" name="password" placeholder="Mot de passe" required autofocus>
    <button type="submit">CONNEXION</button>
  </form>
</div>
</body>
</html>