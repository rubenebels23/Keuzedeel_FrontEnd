<?php
session_start();
require 'db.php';

$sessionId = session_id();

// Fetch cart items
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE session_id = ?");
$stmt->execute([$sessionId]);
$cart = $stmt->fetchAll();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-purple-900 via-purple-700 to-purple-500 min-h-screen text-white">

    <header class="w-full fixed top-0 z-50 bg-black bg-opacity-50 backdrop-blur shadow-md">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-purple-300">Refund4Life</div>

            <!-- hamburger menu -->
            <div class="md:hidden">
                <button id="menu-btn" class="text-white text-3xl focus:outline-none">&#9776;</button>
            </div>

            
            <!-- normal desktop -->
            <ul id="nav-links" class="hidden md:flex space-x-6 text-white font-medium">
                <li><a href="index.php" class="hover:text-purple-400 transition">Home</a></li>
                <li><a href="search.php" class="hover:text-purple-400 transition">Search</a></li>
                <!-- <li><a href="login.php" class="hover:text-purple-400 transition">Login</a></li> -->
                <!-- <li><a href="register.php" class="hover:text-purple-400 transition">Register</a></li> -->
                <li><a href="cart.php" class="hover:text-purple-400 transition">Cart</a></li>
                <li><a href="redeem.php" class="hover:text-purple-400 transition">Redeem</a></li>
            </ul>
        </nav>

        <!-- phone desktop -->
        <div id="mobile-menu" class="md:hidden hidden px-6 pb-4">
            <ul class="flex flex-col space-y-2 text-white font-medium">
                <li><a href="index.php" class="hover:text-purple-400 transition">Home</a></li>
                <li><a href="search.php" class="hover:text-purple-400 transition">Search</a></li>
                <!-- <li><a href="login.php" class="hover:text-purple-400 transition">Login</a></li> -->
                <!-- <li><a href="register.php" class="hover:text-purple-400 transition">Register</a></li> -->
                <li><a href="cart.php" class="hover:text-purple-400 transition">Cart</a></li>
                <li><a href="redeem.php" class="hover:text-purple-400 transition">Redeem</a></li>
            </ul>
        </div>
    </header>

    <script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
    </script>

    <main class="p-8 max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center pt-20">Your Cart</h1>

        <?php if (empty($cart)): ?>
        <p class="text-center text-lg">Your cart is empty.</p>
        <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cart as $item): 
          $lineTotal = $item['price'] * $item['quantity'];
          $total += $lineTotal;
        ?>
            <div class="bg-black bg-opacity-30 p-4 rounded-lg shadow-md text-center">
                <img src="<?= htmlspecialchars($item['thumbnail_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>"
                    class="w-24 h-24 object-cover mx-auto rounded mb-2">
                <h2 class="text-xl font-semibold"><?= htmlspecialchars($item['title']) ?></h2>
                <p class="text-sm">Price: $<?= number_format($item['price'], 2) ?></p>
                <p class="text-sm">Quantity: <?= $item['quantity'] ?></p>
                <p class="text-sm mb-2">Subtotal: $<?= number_format($lineTotal, 2) ?></p>
              <form action="/les1/FrontEnd/remove_from_cart.php" method="POST">



                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Remove</button>
                </form>

            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-right mt-8 text-xl">
            <p>Total: <span class="font-bold">$<?= number_format($total, 2) ?></span></p>
            <form action="checkout.php" method="POST" class="inline-block mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Proceed to
                    Checkout</button>
            </form>
        </div>
        <?php endif; ?>
    </main>

</body>

</html>