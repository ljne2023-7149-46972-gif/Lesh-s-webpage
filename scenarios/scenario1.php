<?php 
$conn = mysqli_connect("localhost", "root", "", "class_db"); 
 
$id = $_POST['id'];  
 


// This query directly places user input into the SQL statement.
// This makes the code vulnerable to SQL Injection attacks.
// A hacker can enter malicious input (e.g., "1 OR 1=1") and access or damage the database.
// FIX: Use prepared statements or sanitize the input before using it in SQL.

$sql = "SELECT * FROM students WHERE id = $id"; 


$res = mysqli_query($conn, $sql); $r = mysqli_fetch_assoc($res); 
 
echo $r['first_name']; ?> 



// 