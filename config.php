<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'myshop');
define('DB_USER', 'root');
define('DB_PASS', ''); // or your MySQL password

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die('Database error: ' . $e->getMessage());
}

session_start();
