<?php
session_start();
require 'db.php';

$sessionId = session_id();
$orderCode = strtoupper(substr(md5(uniqid()), 0, 16));

// Fetch cart items
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE session_id = ?");
$stmt->execute([$sessionId]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    die("Your cart is empty.");
}

// Save the order
$stmt = $pdo->prepare("INSERT INTO orders (session_id, order_code) VALUES (?, ?)");
$stmt->execute([$sessionId, $orderCode]);
$orderId = $pdo->lastInsertId();

// Save each cart item into order_items
$insertItem = $pdo->prepare("INSERT INTO order_items (order_id, title, thumbnail_url, price, quantity) VALUES (?, ?, ?, ?, ?)");
foreach ($cartItems as $item) {
    $insertItem->execute([
        $orderId,
        $item['title'],
        $item['thumbnail_url'],
        $item['price'],
        $item['quantity']
    ]);
}

// Clear cart
$pdo->prepare("DELETE FROM cart_items WHERE session_id = ?")->execute([$sessionId]);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Confirmed</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body 



class="bg-gradient-to-b from-purple-900 via-purple-700 to-purple-500 min-h-screen text-white flex items-center justify-center">
  <div class="bg-black bg-opacity-40 p-8 rounded-lg text-center">
    <h1 class="text-3xl font-bold mb-4">✅ Order Confirmed!</h1>
    <p class="text-lg">Your code is:</p>
    <p class="text-2xl font-mono text-green-300 mt-2"><?= $orderCode ?></p>
    <p class="text-sm mt-4 text-yellow-200">Please save this code. You’ll need it to redeem your game later!</p>
    <a href="index.php" class="mt-6 inline-block text-white underline hover:text-purple-300">Back to home</a>
  </div>
</body>
</html>
