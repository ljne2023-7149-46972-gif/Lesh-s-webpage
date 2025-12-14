<?php
session_start();
include 'database_connect/db_connect.php';

if (!isset($_POST['selected_items']) || empty($_POST['selected_items'])) {
    header("Location: cart.php");
    exit();
}

$selected_ids = $_POST['selected_items'];

$ids_placeholder = implode(',', array_fill(0, count($selected_ids), '?'));
$types = str_repeat('i', count($selected_ids));

$sql = "SELECT sum(quantity * unit_price) as total FROM cart_items WHERE id IN ($ids_placeholder)";
$stmt = $conn->prepare($sql);

$stmt->bind_param($types, ...$selected_ids);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$total = $row['total'] ?? 0.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Mini Treasures</title>
    <script src="cdn/tailwind.js"></script>
    <script src="cdn/feather-unpkg.js"></script>
</head>
<body class="bg-gray-50 p-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>
        
        <form action="actions.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="place_order">
            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
            
            <?php foreach($selected_ids as $id): ?>
                <input type="hidden" name="selected_items[]" value="<?php echo $id; ?>">
            <?php endforeach; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                <div class="text-xl font-bold text-pink-600">$<?php echo number_format((float)$total, 2); ?></div>
            </div>

            <h3 class="text-lg font-medium pt-4">Shipping Address</h3>

            <div>
                <label for="line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                <input type="text" id="line1" name="line1" required 
                       class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-pink-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" id="city" name="city" required 
                           class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-pink-500">
                </div>
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" id="province" name="province" required 
                           class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-pink-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" id="country" name="country" required 
                           class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-pink-500">
                </div>
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" required 
                           class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-pink-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-pink-600 text-white py-3 rounded-md hover:bg-pink-700 mt-6">
                Place Order
            </button>
        </form>
    </div>
</body>
</html>