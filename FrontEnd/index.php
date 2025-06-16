<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund4Life</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-purple-500 via-purple-700 to-black min-h-screen text-white font-sans scroll-smooth">
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

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
    </script>


    <main class="flex items-center justify-center min-h-screen pt-32 text-center px-6">
        <div>
            <h1
                class="text-6xl font-extrabold bg-gradient-to-r from-purple-300 via-white to-purple-300 bg-clip-text text-transparent drop-shadow-md mb-6">
                Refund4Life</h1>

            <a href="search.php"
                class="inline-block px-8 py-4 bg-purple-600 text-white text-lg font-semibold rounded-full shadow-lg hover:shadow-purple-500/50 hover:scale-105 transition-transform duration-300 animate-pulse">
                Search games
            </a>
        </div>
    </main>
    <footer class="text-center py-10 text-sm text-white bg-gradient-to-r from-black to-purple-900">
        &copy; 2025 Refund4Life
    </footer>


</body>

</html>