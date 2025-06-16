<?php
require 'db.php';

$orderItems = [];
$invalidCode = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['order_code']));

    $stmt = $pdo->prepare("SELECT id FROM orders WHERE order_code = ?");
    $stmt->execute([$code]);
    $order = $stmt->fetch();

    if ($order) {
        $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$order['id']]);
        $orderItems = $stmt->fetchAll();
    } else {
        $invalidCode = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Redeem Your Game</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-purple-900 via-purple-700 to-purple-500 min-h-screen text-white">
      <!-- navbar -->
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
  const navLinks = document.getElementById('nav-links');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
    if (navLinks) {
      navLinks.classList.add('hidden');
    }
  });
</script>



  <main class="p-8 max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center pt-32">🎮 Redeem Your Game</h1>

    <form method="POST" class="flex flex-col sm:flex-row items-center gap-4 mb-8">
      <input type="text" name="order_code" placeholder="Enter your code"
        class="w-full sm:w-auto px-4 py-2 text-black rounded focus:ring-2 focus:ring-purple-400" required />
      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
        Redeem
      </button>
    </form>

    <?php if ($invalidCode): ?>
      <p class="text-red-300 text-center">❌ Invalid or unknown code.</p>
    <?php endif; ?>

<?php if (!empty($orderItems)): ?>
  <div class="bg-green-800 bg-opacity-30 p-4 rounded mb-6 text-center text-white">
    <p class="text-lg font-semibold mb-1">✅ We will look over your redeem request.</p>
    <p class="text-sm">When we accept it, the money will be restored to your bank account.</p>
    <p class="text-sm">We have also sent you an email with more details!</p>
    <a href="index.php" class="mt-6 inline-block text-white underline hover:text-purple-300">Back to home</a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <?php foreach ($orderItems as $item): ?>
      <div class="bg-black bg-opacity-30 p-4 rounded text-center">
        <img src="<?= htmlspecialchars($item['thumbnail_url']) ?>" class="w-24 h-24 object-cover mx-auto mb-2 rounded" />
        <h2 class="text-xl font-semibold"><?= htmlspecialchars($item['title']) ?></h2>
        <p>Price: $<?= number_format($item['price'], 2) ?></p>
        <p>Quantity: <?= $item['quantity'] ?></p>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

  </main>

</body>
</html>
