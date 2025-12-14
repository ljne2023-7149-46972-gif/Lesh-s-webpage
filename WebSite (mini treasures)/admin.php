<?php
session_start();
include 'database_connect/db_connect.php';

if (isset($_GET['logout'])) {
    unset($_SESSION['mt_admin_auth']);
    header("Location: login.php");     
    exit();
}

if (isset($_POST['admin_login'])) {
    if ($_POST['password'] === 'admin123') {
        $_SESSION['mt_admin_auth'] = true;
    } else {
        $error = "Incorrect password";
    }
}

if (isset($_SESSION['mt_admin_auth']) && $_SESSION['mt_admin_auth'] === true) {
    if (isset($_POST['update_status'])) {
        $order_id = $_POST['order_id'];
        $new_status_id = $_POST['status_id'];
        
        $stmt = $conn->prepare("UPDATE orders SET order_status_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_status_id, $order_id);
        
        if($stmt->execute()) {
            $msg = "Order #$order_id updated successfully!";
        } else {
            $msg = "Error updating order.";
        }
    }

    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        $conn->query("DELETE FROM users WHERE id = $user_id");
    }
}

$user_count = 0;
$order_count = 0;
$revenue = 0;

if (isset($_SESSION['mt_admin_auth']) && $_SESSION['mt_admin_auth'] === true) {
    $user_count = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
    $order_count = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
    $revenue = $conn->query("SELECT SUM(total_amount) FROM orders")->fetch_row()[0] ?? 0;

    $users_result = $conn->query("SELECT * FROM users ORDER BY id DESC");

    $orders_sql = "SELECT o.id, o.total_amount, o.order_status_id, u.email, os.name as status_name 
                   FROM orders o 
                   JOIN users u ON o.user_id = u.id 
                   LEFT JOIN order_status os ON o.order_status_id = os.id 
                   ORDER BY o.id DESC";
    $orders_result = $conn->query($orders_sql);

    $statuses = $conn->query("SELECT * FROM order_status ORDER BY id ASC");
    $status_options = [];
    while($row = $statuses->fetch_assoc()) {
        $status_options[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard - Mini Treasures</title>
  <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
  <script src="cdn/tailwind.js"></script>
  <script src="cdn/feather-unpkg.js"></script>
  <style>
      .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.8); display: flex; justify-content: center; align-items: center; z-index: 50; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

  <?php if (!isset($_SESSION['mt_admin_auth']) || $_SESSION['mt_admin_auth'] !== true): ?>
  <div class="modal-overlay">
    <div class="bg-white p-8 rounded shadow-lg max-w-sm w-full">
      <h3 class="text-xl font-bold mb-4">Admin Login</h3>
      <?php if(isset($error)) echo "<p class='text-red-500 text-sm mb-2'>$error</p>"; ?>
      <form method="POST">
        <label class="block text-sm mb-2">Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded mb-4" placeholder="Enter admin123">
        <button type="submit" name="admin_login" class="w-full bg-pink-600 text-white py-2 rounded">Sign In</button>
      </form>
      <div class="mt-4 text-center">
          <a href="login.php" class="text-sm text-gray-500 hover:text-pink-600">Back to Main Login</a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <div class="text-lg font-bold text-pink-600">Mini Treasures — Admin</div>
      <div class="flex gap-4">
        <a href="index.php" class="text-gray-600 hover:text-pink-600">View Site</a>
        <a href="admin.php?logout=1" class="text-red-600 font-bold hover:text-red-800">Logout</a>
      </div>
    </div>
  </nav>

  <main class="max-w-7xl mx-auto p-6">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded shadow border-t-4 border-blue-500">
        <div class="text-3xl font-bold"><?php echo $user_count; ?></div>
        <div class="text-gray-500 text-sm">Registered Users</div>
      </div>
      <div class="bg-white p-6 rounded shadow border-t-4 border-yellow-500">
        <div class="text-3xl font-bold"><?php echo $order_count; ?></div>
        <div class="text-gray-500 text-sm">Total Orders</div>
      </div>
      <div class="bg-white p-6 rounded shadow border-t-4 border-green-500">
        <div class="text-3xl font-bold text-green-600">$<?php echo number_format($revenue, 2); ?></div>
        <div class="text-gray-500 text-sm">Total Revenue</div>
      </div>
    </div>

    <?php if(isset($msg)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white p-6 rounded shadow">
          <h3 class="text-lg font-bold mb-4">Recent Orders</h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead>
                <tr class="border-b bg-gray-50">
                  <th class="p-3">ID</th>
                  <th class="p-3">Customer</th>
                  <th class="p-3">Total</th>
                  <th class="p-3">Current Status</th>
                  <th class="p-3">Update Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($order_count > 0): ?>
                  <?php while($order = $orders_result->fetch_assoc()): ?>
                  <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-mono">#<?php echo $order['id']; ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($order['email']); ?></td>
                    <td class="p-3 font-bold text-pink-600">$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            <?php 
                                $sName = $order['status_name'] ?? 'Unknown'; 
                                if($sName == 'Pending') echo 'bg-yellow-100 text-yellow-800';
                                elseif($sName == 'Delivered') echo 'bg-green-100 text-green-800';
                                else echo 'bg-blue-100 text-blue-800';
                            ?>">
                            <?php echo $sName; ?>
                        </span>
                    </td>
                    <td class="p-3">
                      <form method="POST" class="flex items-center gap-2">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status_id" class="border rounded p-1 text-xs">
                            <?php foreach($status_options as $opt): ?>
                                <option value="<?php echo $opt['id']; ?>" 
                                    <?php if($opt['id'] == $order['order_status_id']) echo 'selected'; ?>>
                                    <?php echo $opt['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_status" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Save</button>
                      </form>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="5" class="p-4 text-center text-gray-500">No orders yet.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
          <h3 class="text-lg font-bold mb-4">Registered Users</h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead>
                <tr class="border-b bg-gray-50">
                  <th class="p-3">ID</th>
                  <th class="p-3">Email</th>
                  <th class="p-3">Full Name</th>
                  <th class="p-3">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($user_count > 0): ?>
                  <?php while($u = $users_result->fetch_assoc()): ?>
                  <tr class="border-b">
                    <td class="p-3"><?php echo $u['id']; ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($u['email']); ?></td>
                    <td class="p-3">
                        <?php 
                            $displayName = $u['full_name'] ?? 'User';
                            echo htmlspecialchars($displayName); 
                        ?>
                    </td>
                    <td class="p-3">
                        <form method="POST" onsubmit="return confirm('Delete this user?');">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <button type="submit" name="delete_user" class="text-red-500 hover:text-red-700 font-bold">Delete</button>
                        </form>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="p-4 text-center">No users found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <aside class="space-y-6">
        <div class="bg-white p-6 rounded shadow">
          <h3 class="font-bold mb-2">Admin Guide</h3>
          <p class="text-sm text-gray-600 mb-4">
            Use the <strong>"Update Status"</strong> column to change the progress of an order. 
          </p>
          <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
            <li><strong>Pending:</strong> New order received.</li>
            <li><strong>Shipped:</strong> Handed to courier.</li>
            <li><strong>Out for Delivery:</strong> Rider is on the way.</li>
            <li><strong>Delivered:</strong> Customer received item.</li>
          </ul>
        </div>
      </aside>

    </div>
  </main>
  <script>feather.replace();</script>
</body>
</html>