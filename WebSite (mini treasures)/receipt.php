<?php
session_start();
include 'database_connect/db_connect.php';

$order_id = $_GET['order_id'] ?? 0;
$user_id = $_SESSION['user_id'];

$sql = "SELECT o.id, o.created_at, o.total_amount, a.line1, a.city, a.country, u.full_name 
        FROM orders o
        JOIN address a ON o.address_id = a.id
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) die("Order not found.");

$sqlItems = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE order_id = ?";
$stmt = $conn->prepare($sqlItems);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice #<?php echo $order['id']; ?></title>
    <script src="cdn/tailwind.js"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-3xl mx-auto bg-white p-10 rounded shadow-lg">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Invoice</h1>
            <div class="text-gray-500">Order #<?php echo $order['id']; ?></div>
        </div>

        <div class="mb-8 border-b pb-8">
            <h2 class="text-lg font-bold">Billed To:</h2>
            <p><?php echo $order['full_name']; ?></p>
            <p><?php echo $order['line1']; ?>, <?php echo $order['city']; ?></p>
            <p><?php echo $order['country']; ?></p>
            <p class="mt-2 text-sm text-gray-500">Date: <?php echo $order['created_at']; ?></p>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="text-left border-b">
                    <th class="pb-4">Item</th>
                    <th class="pb-4">Qty</th>
                    <th class="pb-4">Price</th>
                    <th class="pb-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                <tr class="border-b">
                    <td class="py-4"><?php echo $item['name']; ?></td>
                    <td class="py-4"><?php echo $item['quantity']; ?></td>
                    <td class="py-4">$<?php echo $item['unit_price']; ?></td>
                    <td class="py-4 text-right">$<?php echo $item['total_price']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right text-2xl font-bold text-pink-600">
            Total: $<?php echo $order['total_amount']; ?>
        </div>
        
        <div class="mt-8 text-center">
            <button onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700">Print Invoice</button>
            <a href="index.php" class="ml-4 text-pink-600 hover:underline">Return to Home</a>
        </div>
    </div>
</body>
</html>