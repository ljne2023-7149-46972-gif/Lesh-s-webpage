<?php
$servername = "localhost";
$username = "root";     // Default XAMPP username
$password = "";         // Default XAMPP password
$dbname = "mini_treasures_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    // It is okay to echo here because if it fails, the script dies anyway
    die("Connection failed: " . mysqli_connect_error());
}

// IMPORTANT: Do NOT echo "Connected successfully" here. 
// It breaks the JSON response for the registration form.
?>