<?php
// File: php/register.php
header('Content-Type: application/json');
require 'db_connect.php'; // This connects to the file we made in Step 1

// Get JSON data sent from JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['name']) && isset($data['email']) && isset($data['password'])) {
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if($check->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already exists"]);
        exit;
    }

    // Insert into database
    // NOTE: This assumes you ran the ALTER TABLE command from the previous step 
    // to rename 'password_hash' to 'password' and 'full_name' to 'full_name'.
    $sql = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database Error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing data"]);
}
$conn->close();
?>