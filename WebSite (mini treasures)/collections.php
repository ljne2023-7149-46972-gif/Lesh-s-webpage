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
    <title>Collections - Mini Treasures</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="cdn/tailwind.js"></script>
    <script src="cdn/feather-unpkg.js"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-12 w-auto" src="pictures/mtlogo.png" alt="Mini Treasures Logo">
                        <span class="ml-2 text-xl font-bold text-pink-500">Mini Treasures</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</a>
                        <a href="shop.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Shop</a>
                        <a href="collections.php" class="border-pink-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Collections</a>
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
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8">Our Collections</h1>
        
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 w-full bg-gray-200">
                    <img src="pictures/hirono/reshape/reshape ser. cover.jpg" alt="Hirono Collection" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Hirono Series</h2>
                    <p class="text-gray-600 mb-4">The adorable characters by artist Hirono have captured hearts worldwide with their whimsical designs.</p>
                    <a href="shop.php?collection=hirono" class="text-pink-600 hover:text-pink-500 font-medium">View Collection →</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 w-full bg-gray-200">
                    <img src="pictures/skullpanda/sound series/Collection_cover.jpg" alt="Skull Panda Collection" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Skull Panda</h2>
                    <p class="text-gray-600 mb-4">Darkly cute pandas with skull motifs that have become collector favorites.</p>
                    <a href="shop.php?collection=skullpanda" class="text-pink-600 hover:text-pink-500 font-medium">View Collection →</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 w-full bg-gray-200">
                    <img src="pictures/skullpanda/image of reality/image of reality collection cover.jpg" alt="PopMart Collection" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">PopMart Originals</h2>
                    <p class="text-gray-600 mb-4">Signature characters and collaborations from the PopMart brand.</p>
                    <a href="shop.php?collection=popmart" class="text-pink-600 hover:text-pink-500 font-medium">View Collection →</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 w-full bg-gray-200">
                    <img src="pictures/molly/carb lover/carb lover collection cover.jpg" alt="Molly Collection" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Molly World</h2>
                    <p class="text-gray-600 mb-4">From the creative mind of artist Kenny Wong, Molly's adventures continue.</p>
                    <a href="shop.php?collection=molly" class="text-pink-600 hover:text-pink-500 font-medium">View Collection →</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 w-full bg-gray-200">
                    <img src="pictures/Dimoo/weaving wonders/Dimoo weaving and achuchu cover.webp" alt="Zodiac Collection" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Zodiac Series</h2>
                    <p class="text-gray-600 mb-4">Celebrating the 12 zodiac signs with unique artistic interpretations.</p>
                    <a href="shop.php?collection=zodiac" class="text-pink-600 hover:text-pink-500 font-medium">View Collection →</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 w-full bg-gray-200">
                    <img src="pictures/molly/when i was 3/molly wI3 cover.jpg" alt="Mystery Boxes" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Mystery Boxes</h2>
                    <p class="text-gray-600 mb-4">Exclusive blind box series with rare chase figures to discover.</p>
                    <a href="shop.php?collection=mystery" class="text-pink-600 hover:text-pink-500 font-medium">View Collection →</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>