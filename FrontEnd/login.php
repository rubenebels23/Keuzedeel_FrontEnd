<form method="post" action="register.php" autocomplete="off">
  <input name="email" type="email" autocomplete="email" placeholder="Email" required>
  <input name="password" type="password" autocomplete="new-password" placeholder="Password" required>
  <button type="submit">Log In</button>
</form>




<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}