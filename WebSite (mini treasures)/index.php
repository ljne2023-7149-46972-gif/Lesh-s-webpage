<?php
session_start();
include 'database_connect/db_connect.php';

$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $uid_cart = $_SESSION['user_id'];
    $sql_cart = "SELECT SUM(quantity) as total 
                 FROM cart_items ci 
                 JOIN shopping_carts sc ON ci.cart_id = sc.id 
                 WHERE sc.user_id = ?";     
    $stmt_cart = $conn->prepare($sql_cart);
    $stmt_cart->bind_param("i", $uid_cart);
    $stmt_cart->execute();
    $res_cart = $stmt_cart->get_result();
    
    if ($row_cart = $res_cart->fetch_assoc()) {
        $cart_count = $row_cart['total'] ?? 0;
    }
}

$nav_profile_img = 'https://via.placeholder.com/150';
if (isset($_SESSION['user_id'])) {
    $nav_uid = $_SESSION['user_id'];
    $nav_stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
    $nav_stmt->bind_param("i", $nav_uid);
    $nav_stmt->execute();
    $nav_res = $nav_stmt->get_result();
    if ($nav_row = $nav_res->fetch_assoc()) {
        $nav_profile_img = $nav_row['profile_image'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Treasures - PopMart Collectibles</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="cdn/tailwind.js"></script>
    <script src="cdn/feather-unpkg.js"></script>
    <script src="cdn/feather.min.js"></script>
    <script src="cdn/vanta.globe.min.js"></script>
    <script src="cdn/alpine.cdn.min.js" defer></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .page-transition {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0.5; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-12 w-auto" src="pictures/mtLogo.png" alt="Mini Treasures Logo">
                        <span class="ml-2 text-xl font-bold text-pink-500">Mini Treasures</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.php" class="border-pink-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</a>
                        <a href="shop.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Shop</a>
                        <a href="collections.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Collections</a>
                        <a href="about.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">About</a>
</div>
</div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <a href="search.php" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i data-feather="search"></i>
                    </a>
                    <a href="account.php" class="ml-3 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                        <a href="account.php" class="text-gray-600 hover:text-pink-600 flex items-center justify-center">
                        <?php 
                             if (isset($_SESSION['user_id']) && $nav_profile_img !== 'https://via.placeholder.com/150'): 
                        ?>
                             <img src="<?php echo htmlspecialchars($nav_profile_img); ?>" 
                                alt="Profile" 
                                class="w-6 h-6 rounded-full object-cover border border-gray-200">
                        <?php else: ?>
                            <i data-feather="user"></i>
                        <?php endif; ?>
                    </a>
                    </a>
                    <a href="cart.php" class="ml-3 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none relative">
                        <i data-feather="shopping-cart"></i>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-pink-500 rounded-full">
                            <span id="cart-count"><?php echo $cart_count; ?></span>
                        </span>
                    </a>
                   <?php if (isset($_SESSION['user_name'])): ?>
                        <span class="ml-4 px-4 py-2 text-sm font-bold text-white bg-pink-600 rounded-full shadow-md transition transform hover:scale-105 cursor-default">
                            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                    <a href="logout.php" class="ml-2 px-4 py-2 text-sm font-medium text-pink-600 bg-white border border-pink-200 rounded-full hover:bg-pink-50 transition-colors">
                         Logout
                    </a>
                    <?php endif; ?>
</div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <i data-feather="menu"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <!-- landing page -->
    <div id="hero" class="bg-gradient-to-r from-pink-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center py-16 md:py-24">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block">Collect. Display.</span>
                        <span class="block text-pink-500">Cherish.</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl">
                        Handcrafted collectibles that tell stories. Limited editions from top artists worldwide.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <a id="start-collecting-btn" href="login.php" class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 md:py-4 md:text-lg md:px-10">
                            Start Collecting
                        </a>
                        <a href="collections.php" class="px-8 py-3 border border-pink-500 text-base font-medium rounded-md text-pink-600 bg-white hover:bg-pink-50 md:py-4 md:text-lg md:px-10">
                            View Gallery
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="relative">
                        <div class="absolute -top-5 -left-5 w-full h-full rounded-2xl bg-pink-200 z-0"></div>
                        <div class="relative z-10 rounded-2xl overflow-hidden shadow-xl">
                            <img src="pictures/bgmain.png" alt="Featured Collectible" class="w-full h-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured Collections -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Curated Collections
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Handpicked selections from our most popular series
                </p>
            </div>
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
<div class="group relative">
                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                    <img src="pictures/hirono/reshape/reshape ser. cover.jpg" alt="Hirono Collection" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                </div>
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Hirono Reshape Series
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Complete set of 12</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$89.99</p>
                </div>
            </div>
            <div class="group relative">
                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                    <img src="pictures/skullpanda/sound series/Collection_cover.JPG" alt="Skull Panda Collection" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                </div>
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Skull Panda Sound Series
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Limited edition</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$59.99</p>
                </div>
            </div>
            <div class="group relative">
                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                    <img src="pictures/Dimoo/weaving wonders/Dimoo weaving and achuchu cover.webp" alt="PopMart Collection" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                </div>
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Dimoo Weaving Wonders Series
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">12 unique designs</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$79.99</p>
                </div>
            </div>
            <div class="group relative">
                <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                    <img src="pictures/molly/when i was 3/molly wI3 cover.jpg" alt="Molly Collection" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                </div>
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Molly "When I was 3" Series
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Chase variant included</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$109.99</p>
                </div>
            </div>
        </div>
    </div>
    <!-- New Arrivals -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">New & Notable</h2>
                    <p class="mt-2 text-lg text-gray-500">Fresh additions to our treasure trove</p>
                </div>
                <a href="login.p" class="text-pink-600 hover:text-pink-500 font-medium">View all →</a>
            </div>
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
<div class="group relative product-card transition duration-300 ease-in-out">
                    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none relative">
                        <img src="pictures/hirono/city of mercy/city of mercy collection cover.jpeg" alt="New Arrival 1" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                        <div class="absolute top-2 right-2 bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</div>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a href="#">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Hirono City of Mercy Series
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Series 4</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$29.99</p>
                    </div>
                </div>
                <div class="group relative product-card transition duration-300 ease-in-out">
                    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none relative">
                        <img src="pictures/skullpanda/image of reality/image of reality collection cover.jpg" alt="New Arrival 2" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                        <div class="absolute top-2 right-2 bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</div>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a href="#">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Skull Image of Reality Series
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Exclusive edition</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$39.99</p>
                    </div>
                </div>
                <div class="group relative product-card transition duration-300 ease-in-out">
                    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none relative">
                        <img src="pictures/Dimoo/Shapes in nature/Shapes in nature collection cover.jpg" alt="New Arrival 3" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                        <div class="absolute top-2 right-2 bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</div>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a href="#">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Dimoo Shapes in Nature Series
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Limited release</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$49.99</p>
                    </div>
                </div>
                <div class="group relative product-card transition duration-300 ease-in-out">
                    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none relative">
                        <img src="pictures/molly/carb lover/carb lover collection cover.jpg" alt="New Arrival 4" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                        <div class="absolute top-2 right-2 bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</div>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a href="#">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Molly Carb Lover Series
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">New Set</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$19.99</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Section -->
    <div class="relative bg-pink-600 overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-10" src="http://static.photos/toys/1200x630/42" alt="Background pattern">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                    <span class="block">Become a VIP Collector</span>
                </h2>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-pink-100">
                    Early access to drops, exclusive content, and 10% off your first purchase.
                </p>
                    <div class="mt-10 sm:flex sm:justify-center">
                    <div class="rounded-md shadow">
                        <a href="register.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-pink-600 bg-white hover:bg-pink-50 md:py-4 md:text-lg md:px-10">
                            Join Now
                        </a>
                    </div>
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                        <a href="login.php" class="w-full flex items-center justify-center px-8 py-3 border border-white text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-50 md:py-4 md:text-lg md:px-10">
                            Log-In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Shop</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">All Products</a></li>
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">New Arrivals</a></li>
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">Limited Editions</a></li>
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">Mystery Boxes</a></li>
</ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Collections</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">Hirono</a></li>
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">Skull Panda</a></li>
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">Dimoo</a></li>
                        <li><a href="login.php" class="text-base text-gray-400 hover:text-white">Molly</a></li>
</ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Support</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">FAQs</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Shipping</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Returns</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Connect</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Instagram</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Facebook</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Twitter</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Discord</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-700 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; 2025 Mini Treasures. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    <script>
        // Redirect shop links to login
        document.querySelectorAll('a[href="#"]').forEach(link => {
            if(link.textContent.toLowerCase().includes('shop') || 
               link.textContent.toLowerCase().includes('collection')) {
                link.href = 'login.php';
            }
        });
        feather.replace();

        // If user is already logged in, remove the Start Collecting button
        try{
            const logged = localStorage.getItem('mt_userEmail');
            if(logged){
                const sc = document.getElementById('start-collecting-btn');
                if(sc) sc.remove();
                // also update navbar welcome if user info exists
                const users = JSON.parse(localStorage.getItem('mt_users')||'{}');
                const name = (users[logged] && users[logged].name) ? users[logged].name : '';
                const welcome = document.getElementById('user-welcome');
                const logoutBtn = document.getElementById('logout-btn');
                if(welcome){ welcome.textContent = name ? ('Welcome, ' + name) : 'Welcome'; welcome.classList.remove('hidden'); }
                if(logoutBtn) logoutBtn.classList.remove('hidden');
            }
        }catch(e){ console.warn('StartCollect check failed', e); }
    </script>
    <div id="loginModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 page-transition">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Sign In</h3>
                <button onclick="document.getElementById('loginModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-500">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-pink-600 focus:ring-pink-500">
                        <label class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>
                    <a href="#" class="text-sm text-pink-600 hover:text-pink-500">Forgot password?</a>
                </div>
                <button type="submit" class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700">
                    Sign In
                </button>
                <div class="text-center text-sm text-gray-600">
                    Don't have an account? <a href="register.php" class="text-pink-600 hover:text-pink-500">Register</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Page transition animation
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('page-transition');
            
            // Make all internal links use the transition
            document.querySelectorAll('a[href^="/"], a[href^="."]').forEach(link => {
                link.addEventListener('click', (e) => {
                    if (link.href.includes('login.php') || link.href.includes('register.php')) {
                        e.preventDefault();
                        document.getElementById('loginModal').classList.remove('hidden');
                        return;
                    }
                    
                    e.preventDefault();
                    document.body.classList.remove('page-transition');
                    setTimeout(() => {
                        window.location.href = link.href;
                    }, 300);
                });
            });
        });

        feather.replace();
    </script>
  </body>>