<?php
$conn = mysqli_connect("localhost","root","","class_db");
$id = $_POST['id'];
$email = $_POST['email'];

//  $email is missing quotes → SQL error if it's a string
//  "Updated!" is always printed even if the query fails
//  Add quotes around $email and check mysqli_query() return value before echoing
$sql = "UPDATE students SET email=$email WHERE id=$id";
$res = mysqli_query($conn, $sql);
echo "Updated!";
?>
