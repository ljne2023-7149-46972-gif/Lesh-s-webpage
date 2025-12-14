<?php
session_start();
include 'database_connect/db_connect.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($action == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("SELECT id FROM shopping_carts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $cart_id = $res->fetch_assoc()['id'];
    } else {
        $stmt = $conn->prepare("INSERT INTO shopping_carts (user_id) VALUES (?)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_id = $stmt->insert_id;
    }

    $stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $new_qty = $row['quantity'] + 1;
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_qty, $row['id']);
    } else {
        $qty = 1;
        $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $cart_id, $product_id, $qty, $price);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit();
}

if ($action == 'remove_item') {
    $item_id = $_POST['item_id'];
    $stmt = $conn->prepare("DELETE ci FROM cart_items ci 
                           JOIN shopping_carts sc ON ci.cart_id = sc.id 
                           WHERE ci.id = ? AND sc.user_id = ?");
    $stmt->bind_param("ii", $item_id, $user_id);
    if($stmt->execute()) {
        header("Location: cart.php"); 
    }
    exit();
}

if ($action == 'place_order') {
    $address_line1 = $_POST['line1'];       
    $city = $_POST['city'];
    $province = $_POST['province'];         
    $zip = $_POST['postal_code'];           
    $country = $_POST['country'];
    $total_amount = $_POST['total_amount'];
    $selected_items = $_POST['selected_items'] ?? []; 

    if (empty($selected_items)) {
        die("No items selected for checkout.");
    }

    $stmt = $conn->prepare("INSERT INTO address (line1, city, province, postal_code, country, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW()) 
        ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
    $stmt->bind_param("sssss", $address_line1, $city, $province, $zip, $country);
    
    if (!$stmt->execute()) {
        die("Error saving address: " . $stmt->error);
    }
    $address_id = $stmt->insert_id;

    $status_id = 1; 
    $stmt = $conn->prepare("INSERT INTO orders (user_id, address_id, order_status_id, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $user_id, $address_id, $status_id, $total_amount);
    
    if (!$stmt->execute()) {
        die("Error creating order: " . $stmt->error);
    }
    $order_id = $stmt->insert_id;

    foreach ($selected_items as $ci_id) {
        $q = $conn->prepare("SELECT product_id, quantity, unit_price FROM cart_items WHERE id = ?");
        $q->bind_param("i", $ci_id);
        $q->execute();
        $item = $q->get_result()->fetch_assoc();

        if ($item) {
            $line_total = $item['quantity'] * $item['unit_price'];

            $ins = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
            $ins->bind_param("iiidd", $order_id, $item['product_id'], $item['quantity'], $item['unit_price'], $line_total);
            $ins->execute();

            $del = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
            $del->bind_param("i", $ci_id);
            $del->execute();
        }
    }

    header("Location: receipt.php?order_id=" . $order_id);
    exit();
}
?>