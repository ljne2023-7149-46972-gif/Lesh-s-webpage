<?php 
$conn = mysqli_connect("localhost","root","","class_db"); 

$id = $_POST['id']; 
$email = $_POST['email']; 

// Missing quotes around $email, causing SQL error
// Code prints "Updated!" even if query fails
// Add quotes around $email because they're a value
// Add error checking with mysqli_query() return value
$sql = "UPDATE students SET email=$email WHERE id=$id"; 
$res = mysqli_query($conn, $sql); 

echo "Updated!"; 
?> 
