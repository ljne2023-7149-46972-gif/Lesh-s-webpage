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

$search_error = "";
$search_query = "";

if (isset($_GET['q'])) {
    $search_query = trim($_GET['q']);
    
    if (!empty($search_query)) {
        $stmt = $conn->prepare("SELECT id FROM products WHERE name LIKE ? OR description LIKE ? LIMIT 1");
        $searchTerm = "%" . $search_query . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: shop.php?q=" . urlencode($search_query));
            exit();
        } else {
            $search_error = "Search not found";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Mini Treasures</title>
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
                    <a href="collections.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Collections</a>
                    <a href="about.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">About</a>
                </div>
            </div>
            
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <a href="search.php" class="bg-white p-1 rounded-full text-pink-500 hover:text-pink-700 focus:outline-none">
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
    <div class="max-w-lg mx-auto">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8 text-center">Search</h1>
        
        <form action="search.php" method="GET" class="relative">
            <input id="search-input" name="q" type="text" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                   placeholder="Search for products..." 
                   value="<?php echo htmlspecialchars($search_query); ?>">
                   
            <button type="submit" id="search-btn" class="absolute right-3 top-3 text-gray-400 hover:text-gray-500" aria-label="Search">
                <i data-feather="search"></i>
            </button>
        </form>

        <?php if ($search_error): ?>
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i data-feather="alert-circle" class="h-5 w-5 text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            <?php echo $search_error; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    feather.replace();
</script>
</body>
</html>