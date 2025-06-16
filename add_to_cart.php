<?php
session_start();
require 'db.php';

$sessionId = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['gb_game_id'] ?? '';
    $title = $_POST['title'] ?? 'Unknown Game';
    $thumb = $_POST['thumbnail_url'] ?? '';
    $price = $_POST['price'] ?? 0;

    // Check if already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE session_id = ? AND gb_game_id = ?");
    $stmt->execute([$sessionId, $id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items (session_id, gb_game_id, title, thumbnail_url, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$sessionId, $id, $title, $thumb, $price]);
    }

    header('Location: cart.php');
    exit;
}
?>
