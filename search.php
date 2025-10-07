<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Refund4Life - Game Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-purple-900 via-purple-700 to-purple-500 min-h-screen text-white">

     <!-- navbar -->
    <header class="w-full top-0 z-50 bg-black bg-opacity-50 backdrop-blur shadow-md">
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

    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
    </script>

  <!-- Main -->
<main class="flex flex-col items-center p-8 pt-15">
    <div class="relative w-full max-w-xl">
      <input id="searchInput" type="text" placeholder="Search your favorite game..."
        class="w-full px-4 py-3 rounded-full text-black placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-400" />
      <div id="search-icon" class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer">
        <svg stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="url(#cosmic-search)" fill="none"
          height="24" width="24" viewBox="0 0 24 24">
          <circle r="8" cy="11" cx="11"></circle>
          <line y2="16.65" x2="16.65" y1="21" x1="21"></line>
          <defs>
            <linearGradient gradientTransf    orm="rotate(45)" id="cosmic-search">
                <stop stop-color="#black" offset="0%"></stop>
                <stop stop-color="#a78bfa" offset="100%"></stop>
            </linearGradient>
          </defs>
        </svg>
      </div>
    </div>

    <div id="results" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 w-full max-w-6xl"></div>
  </main>

  <!-- Modal -->
  <div id="gameModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
    <div class="bg-white text-black rounded-lg p-6 w-full max-w-2xl relative">
      <button class="absolute top-2 right-2 text-black text-xl close-button">&times;</button>
      <h2 id="modal-title" class="text-2xl font-bold mb-2"></h2>
      <p id="modal-description" class="mb-4"></p>

<div class="flex flex-wrap gap-2 mb-4" id="modal-buttons">
  <button onclick="fetchExtra('characters')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Characters</button>
  <button onclick="fetchExtra('locations')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Locations</button>
  <button onclick="fetchExtra('concepts')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Concepts</button>
  <button onclick="fetchExtra('platforms')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Platforms</button>
  <button onclick="fetchExtra('genres')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Genres</button>
  <button onclick="fetchExtra('releases')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Releases</button>
  <button onclick="fetchExtra('developers')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Developers</button>
  <button onclick="fetchExtra('publishers')" class="px-4 py-2 rounded border border-purple-400 text-purple-300 hover:bg-purple-700 hover:text-black transition">Publishers</button>
</div>

      <form method="POST" action="add_to_cart.php" id="addToCartForm" class="mb-4">
        <input type="hidden" name="gb_game_id" id="formGameId">
        <input type="hidden" name="title" id="formGameTitle">
        <input type="hidden" name="thumbnail_url" id="formGameThumb">
        <input type="hidden" name="price" id="formGamePrice">
        <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800">Add to cart 🛒</button>
      </form>

      <div id="modal-results" class="space-y-2 overflow-y-auto max-h-64 pr-2"></div>

    </div>
  </div>

<script>window.API = "http://localhost:5050";</script>
<script src="script.js?v=force-5050-1"></script>




  <script src="./script.js"></script>
</body>

</html>
