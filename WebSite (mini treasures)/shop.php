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
    <title>Shop - Mini Treasures</title>
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
                        <a href="shop.php" class="border-pink-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Shop</a>
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
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8">Shop All Products</h1>
        <div id="search-results-info" class="text-sm text-gray-600 mb-6"></div>
        
        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $imgSql = "SELECT url FROM product_images WHERE product_id = " . $row['id'] . " AND is_primary = 1 LIMIT 1";
                    $imgRes = $conn->query($imgSql);
                    $imgUrl = ($imgRes->num_rows > 0) ? $imgRes->fetch_assoc()['url'] : 'pictures/placeholder.jpg';
                    ?>
                    
                    <div class="group relative bg-white rounded-lg p-2 transition-all duration-300 hover:shadow-2xl hover:scale-105 hover:ring-2 hover:ring-pink-500">
                        <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden lg:h-80 lg:aspect-none relative">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-full object-center object-cover lg:w-full lg:h-full transition-transform duration-500 group-hover:scale-110">
                            
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black bg-opacity-20">
                                <button onclick="addToCart(<?php echo $row['id']; ?>, <?php echo $row['price']; ?>)" 
                                        class="bg-pink-600 text-white font-bold py-3 px-6 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-pink-700 hover:scale-110">
                                    Add to Cart
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700 font-bold">
                                    <?php echo $row['name']; ?>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500"><?php echo $row['description']; ?></p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">$<?php echo $row['price']; ?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('page-transition');
            feather.replace();
        });

        async function addToCart(id, price) {
            const formData = new FormData();
            formData.append('action', 'add_to_cart');
            formData.append('product_id', id);
            formData.append('price', price);

            try {
                const response = await fetch('actions.php', { method: 'POST', body: formData });
                const result = await response.json();
                
                if(result.status === 'success') {
                    const cartCount = document.getElementById('cart-count');
                    if(cartCount) cartCount.innerText = parseInt(cartCount.innerText) + 1;
                    alert("Added to cart!");
                } else {
                    alert(result.message);
                    if(result.message.includes('login')) window.location.href = 'login.php';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        (function(){
            function getQuery(){
                const params = new URLSearchParams(window.location.search);
                return params.get('q') || '';
            }
            function applyFilter(q){
                const info = document.getElementById('search-results-info');
                const cards = Array.from(document.querySelectorAll('.group.relative'));
                if(!q){
                    info.textContent = '';
                    cards.forEach(c=> c.style.display = '');
                    return;
                }
                const qq = q.toLowerCase();
                let matchCount = 0;
                cards.forEach(c=>{
                    const text = (c.innerText || '').toLowerCase();
                    if(text.includes(qq)){
                        c.style.display = '';
                        matchCount++;
                    } else {
                        c.style.display = 'none';
                    }
                });
                info.textContent = `Showing ${matchCount} result${matchCount===1?'':'s'} for "${q}"`;
                if(matchCount===0) info.textContent = `No results for "${q}"`;
            }
            document.addEventListener('DOMContentLoaded', ()=>{
                const q = getQuery();
                if(q) applyFilter(q);
            });
        })();
    </script>
</body>
</html>