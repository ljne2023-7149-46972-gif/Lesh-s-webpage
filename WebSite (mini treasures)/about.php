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
    <title>About - Mini Treasures</title>
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
                        <a href="about.php" class="border-pink-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">About</a>
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

    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                Our Story
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500">
                Bringing joy through collectible art since 2018
            </p>
        </div>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-8">
            <div>
                <div class="prose prose-lg text-gray-500">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About Mini Treasures</h2>
                    <p>
                        Founded in 2018, Mini Treasures began as a passion project by collectors, for collectors. 
                        What started as a small online shop for designer toys has grown into a destination for 
                        exclusive collectibles from artists around the world.
                    </p>
                    <p>
                        We specialize in limited edition vinyl toys, art figures, and designer collectibles that 
                        bring creative visions to life in three-dimensional form. Each piece in our collection 
                        is carefully curated for its artistic merit and collectible value.
                    </p>
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Our Mission</h2>
                    <p>
                        To connect collectors with extraordinary works of art in miniature form. We believe 
                        these small treasures can bring big joy, spark creativity, and connect people through 
                        shared appreciation for innovative design.
                    </p>
                </div>
            </div>
            <div class="space-y-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Our Values</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-pink-500">
                                <i data-feather="heart"></i>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <strong>Passion for Art:</strong> We celebrate creativity in all its forms
                            </p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-pink-500">
                                <i data-feather="globe"></i>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <strong>Global Community:</strong> Connecting collectors worldwide
                            </p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-pink-500">
                                <i data-feather="award"></i>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <strong>Quality First:</strong> Only the finest collectibles make our shelves
                            </p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-pink-500">
                                <i data-feather="gift"></i>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <strong>Joy of Collecting:</strong> Spreading happiness one treasure at a time
                            </p>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gradient-to-r from-pink-50 to-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Meet the Team</h3>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <img class="h-12 w-12 rounded-full object-cover bg-gray-200" src="pictures/Team/S.jpg" alt="Rovi Shean Salalima">
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm">Rovi Shean Salalima</h4>
                            <p class="text-xs text-gray-600">Team Member</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <img class="h-12 w-12 rounded-full object-cover bg-gray-200" src="pictures/Team/A.jpg" alt="Aiko Jean Suzuki">
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm">Aiko Jean Suzuki</h4>
                            <p class="text-xs text-gray-600">Team Member</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <img class="h-12 w-12 rounded-full object-cover bg-gray-200" src="pictures/Team/K.jpg" alt="Kirly Joy Gomez">
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm">Kirly Joy Gomez</h4>
                            <p class="text-xs text-gray-600">Team Member</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <img class="h-12 w-12 rounded-full object-cover bg-gray-200" src="pictures/Team/L.jpg" alt="Leshann Jarred Encinares">
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm">Leshann Jarred Encinares</h4>
                            <p class="text-xs text-gray-600">Team Member</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <img class="h-12 w-12 rounded-full object-cover bg-gray-200" src="pictures/Team/C.png" alt="Carl Guianne Garcia">
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm">Carl Guianne Garcia</h4>
                            <p class="text-xs text-gray-600">Team Member</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>