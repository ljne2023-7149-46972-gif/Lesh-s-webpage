<?php
session_start();
include 'database_connect/db_connect.php';

$cart_items = [];
$cart_count = 0; 

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    $sql = "SELECT ci.id as cart_item_id, p.name, p.price, ci.quantity, pi.url 
            FROM cart_items ci
            JOIN shopping_carts sc ON ci.cart_id = sc.id
            JOIN products p ON ci.product_id = p.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE sc.user_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($cart_items as $item) {
            $cart_count += $item['quantity'];
        }
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
    <title>Cart - Mini Treasures</title>
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
                    <a href="cart.php" class="ml-3 bg-white p-1 rounded-full text-pink-500 hover:text-pink-700 focus:outline-none relative">
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
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8">Shopping Cart</h1>
        
        <form action="checkout.php" method="POST" id="cart-form">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    <?php if (empty($cart_items)): ?>
                        <li class="p-10 text-center text-gray-500 text-lg">
                            <p class="mb-4">Your cart is currently empty.</p>
                            <a href="shop.php" class="text-pink-600 hover:text-pink-500 font-medium">Go Shopping →</a>
                        </li>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): ?>
                        <li class="px-6 py-4 flex items-center">
                            <div class="flex items-center h-5 mr-4">
                                <input type="checkbox" name="selected_items[]" value="<?php echo $item['cart_item_id']; ?>" checked class="focus:ring-pink-500 h-5 w-5 text-pink-600 border-gray-300 rounded">
                            </div>
                            
                            <div class="flex-shrink-0 h-16 w-16 border border-gray-200 rounded-md overflow-hidden">
                                <img src="<?php echo $item['url'] ?? 'pictures/placeholder.jpg'; ?>" class="w-full h-full object-center object-cover">
                            </div>
                            
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between font-medium text-gray-900">
                                    <h3><?php echo $item['name']; ?></h3>
                                    <p class="ml-4">$<?php echo $item['price']; ?></p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            
                            <button type="button" onclick="removeItem(<?php echo $item['cart_item_id']; ?>)" class="ml-4 text-sm font-medium text-red-600 hover:text-red-500 bg-transparent border border-red-200 rounded px-3 py-1 hover:bg-red-50">
                                Remove
                            </button>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if (!empty($cart_items)): ?>
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-pink-600 border border-transparent rounded-md shadow-sm py-3 px-8 text-base font-medium text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    Proceed to Checkout
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <script>
        feather.replace();

        function removeItem(id) {
            if(confirm('Are you sure you want to remove this item?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'actions.php';
                
                const inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = 'remove_item';
                
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'item_id';
                inputId.value = id;
                
                form.appendChild(inputAction);
                form.appendChild(inputId);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    </body>
</html>