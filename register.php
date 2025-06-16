<form method="post" action="register.php" autocomplete="off">
  <input name="username" autocomplete="username" placeholder="Username" required>
  <input name="email" type="email" autocomplete="email" placeholder="Email" required>
  <input name="password" type="password" autocomplete="new-password" placeholder="Password" required>
  <button type="submit">Register</button>
</form>


<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "<p style='color:red'>Email already registered. Please use another.</p>";
        exit;
    }

    // Hash and insert
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hash]);

    // Create cart
    $userId = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt2->execute([$userId]);

    $_SESSION['user_id'] = $userId;
    header('Location: index.php');
    exit;
}


