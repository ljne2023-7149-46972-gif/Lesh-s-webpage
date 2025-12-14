<?php
session_start();
include 'database_connect/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$view = $_GET['view'] ?? 'purchases';

if (isset($_POST['action']) && $_POST['action'] === 'update_avatar') {
    $new_image = $_POST['image_url'];
    $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    $stmt->bind_param("si", $new_image, $user_id);
    $stmt->execute();
    header("Location: account.php"); 
    exit();
}

$user_stmt = $conn->prepare("SELECT first_name, last_name, email, profile_image FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_info = $user_stmt->get_result()->fetch_assoc();

$full_name = ($user_info['first_name'] ?? 'User') . ' ' . ($user_info['last_name'] ?? '');
$email = $user_info['email'] ?? '';
$profile_pic = !empty($user_info['profile_image']) ? $user_info['profile_image'] : 'https://via.placeholder.com/150';

$avatar_options = $conn->query("SELECT DISTINCT url FROM product_images LIMIT 20");

$delivered_items = [];
$active_items = [];

if ($view === 'purchases' || $view === 'status') {
    $sql = "SELECT 
                p.name AS product_name, 
                oi.quantity, 
                oi.unit_price, 
                os.name AS status_name, 
                os.id AS status_id,
                o.created_at
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN products p ON oi.product_id = p.id
            JOIN order_status os ON o.order_status_id = os.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if ($row['status_id'] == 4) {
            $delivered_items[] = $row;
        } else {
            $active_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Mini Treasures</title>
    <script src="cdn/tailwind.js"></script>
    <script src="cdn/feather-unpkg.js"></script>
    <style>
        .avatar-overlay { background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.2s; }
        .avatar-container:hover .avatar-overlay { opacity: 1; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 50; align-items: center; justify-content: center; }
        .modal.open { display: flex; }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow p-4 mb-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img class="h-8 w-auto mr-2" src="pictures/mtlogo.png" alt="Mini Treasures Logo">
                <div class="text-xl font-bold text-pink-600">Mini Treasures</div>
            </div>
            
            <div>
                <a href="index.php" class="mr-4 hover:text-pink-600">Home</a>
                <a href="logout.php" class="text-sm border border-pink-600 text-pink-600 px-3 py-1 rounded hover:bg-pink-600 hover:text-white transition">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Account</h1>

        <div class="flex flex-col md:flex-row gap-8">
            
            <div class="w-full md:w-64 flex-shrink-0">
                
                <div class="bg-white rounded-lg shadow p-6 mb-6 text-center">
                    
                    <div class="avatar-container w-28 h-28 mx-auto relative rounded-full border-4 border-pink-100 overflow-hidden mb-4 cursor-pointer" onclick="openModal()">
                        <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile" class="w-full h-full object-cover">
                        <div class="avatar-overlay absolute inset-0 flex items-center justify-content-center text-white text-xs font-bold">
                            CHANGE
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-gray-900 text-xl mb-1"><?php echo htmlspecialchars($full_name); ?></h3>
                    
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($email); ?></p>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <nav class="flex flex-col">
                        <a href="account.php?view=purchases" 
                           class="px-6 py-4 text-sm font-medium border-l-4 transition-colors duration-200
                           <?php echo ($view == 'purchases') ? 'border-pink-600 text-pink-600 bg-pink-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                            My Purchases
                        </a>

                        <a href="account.php?view=status" 
                           class="px-6 py-4 text-sm font-medium border-l-4 transition-colors duration-200
                           <?php echo ($view == 'status') ? 'border-pink-600 text-pink-600 bg-pink-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                            Order Status
                        </a>

                        <a href="account.php?view=settings" 
                           class="px-6 py-4 text-sm font-medium border-l-4 transition-colors duration-200
                           <?php echo ($view == 'settings') ? 'border-pink-600 text-pink-600 bg-pink-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                            Settings
                        </a>
                        <a href="account.php?view=chat" 
                           class="px-6 py-4 text-sm font-medium border-l-4 transition-colors duration-200
                           <?php echo ($view == 'chat') ? 'border-pink-600 text-pink-600 bg-pink-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                            Chat
                        </a>
                    </nav>
                </div>
            </div>

            <div class="flex-1">
                <div class="bg-white rounded-lg shadow min-h-[400px] p-6">
                    
                    <?php if ($view == 'purchases'): ?>
                        <h2 class="text-xl font-bold mb-4 text-gray-800">My Purchases (Delivered)</h2>
                        <?php if (empty($delivered_items)): ?>
                            <div class="text-gray-500 text-center py-10">No delivered items yet.</div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-50 border-b">
                                        <tr>
                                            <th class="py-3 px-4">Product</th>
                                            <th class="py-3 px-4">Qty</th>
                                            <th class="py-3 px-4">Price</th>
                                            <th class="py-3 px-4">Status</th>
                                            <th class="py-3 px-4">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php foreach ($delivered_items as $item): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td class="py-3 px-4"><?php echo $item['quantity']; ?></td>
                                            <td class="py-3 px-4 text-pink-600">$<?php echo number_format($item['unit_price'], 2); ?></td>
                                            <td class="py-3 px-4"><span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Delivered</span></td>
                                            <td class="py-3 px-4"><?php echo date('M j, Y', strtotime($item['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    <?php elseif ($view == 'status'): ?>
                        <h2 class="text-xl font-bold mb-4 text-gray-800">Order Status (Active)</h2>
                        <?php if (empty($active_items)): ?>
                            <div class="text-gray-500 text-center py-10">No active orders right now.</div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-50 border-b">
                                        <tr>
                                            <th class="py-3 px-4">Product</th>
                                            <th class="py-3 px-4">Qty</th>
                                            <th class="py-3 px-4">Price</th>
                                            <th class="py-3 px-4">Status</th>
                                            <th class="py-3 px-4">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php foreach ($active_items as $item): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td class="py-3 px-4"><?php echo $item['quantity']; ?></td>
                                            <td class="py-3 px-4 text-pink-600">$<?php echo number_format($item['unit_price'], 2); ?></td>
                                            <td class="py-3 px-4">
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                    <?php echo $item['status_name']; ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4"><?php echo date('M j, Y', strtotime($item['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    <?php elseif ($view == 'settings'): ?>
                        <h2 class="text-xl font-bold mb-4 text-gray-800">Account Settings</h2>
                        <p class="text-gray-600">Here you can change your password or update your shipping address.</p>

                    <?php elseif ($view == 'chat'): ?>
                        <h2 class="text-xl font-bold mb-4 text-gray-800">Chat Support</h2>
                        <div class="bg-gray-100 h-64 rounded flex items-center justify-center text-gray-500">
                            Chat feature coming soon...
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div id="avatarModal" class="modal">
        <div class="bg-white rounded-lg p-6 max-w-lg w-full m-4">
            <h3 class="text-lg font-bold mb-4">Choose a Profile Picture</h3>
            
            <div class="grid grid-cols-4 gap-4 max-h-60 overflow-y-auto mb-4">
                <?php while($img = $avatar_options->fetch_assoc()): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_avatar">
                        <input type="hidden" name="image_url" value="<?php echo htmlspecialchars($img['url']); ?>">
                        <button type="submit" class="w-full aspect-square border-2 border-transparent hover:border-pink-500 rounded overflow-hidden">
                            <img src="<?php echo htmlspecialchars($img['url']); ?>" class="w-full h-full object-cover">
                        </button>
                    </form>
                <?php endwhile; ?>
            </div>

            <div class="text-right">
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        function openModal() { document.getElementById('avatarModal').classList.add('open'); }
        function closeModal() { document.getElementById('avatarModal').classList.remove('open'); }
    </script>
</body>
</html>