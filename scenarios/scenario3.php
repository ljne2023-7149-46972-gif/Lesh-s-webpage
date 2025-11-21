<?php 
$conn = mysqli_connect("localhost","root","","class_db"); 

$age = $_GET['age']; 

$sql = "SELECT * FROM students WHERE age = $age"; 

$res = mysqli_query($conn, $sql); 

//  The value from $_GET['age'] is placed directly into the SQL query.
// This makes the code vulnerable to SQL Injection, where a user can inject
// harmful SQL commands (e.g., ?age=1 OR 1=1). It also assumes the input is always valid.
// Validate/sanitize the input and use prepared statements to securely
// bind the variable instead of inserting it directly into the SQL string.
?> 


