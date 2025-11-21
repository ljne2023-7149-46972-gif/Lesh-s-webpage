<?php 
$conn = mysqli_connect("localhost","root","","class_db"); 

$fname = $_POST['fname']; 

// PROBLEM: The value of $fname is placed directly into the SQL query without quotes
// Without any validation or sanitization. This can cause SQL Injection,
// The query will also fail if the name contains spaces or special characters.
// The Use quotes in the SQL string and preferably use prepared statements to secure the query.

$sql = "SELECT * FROM students WHERE first_name = $fname";  

$res = mysqli_query($conn, $sql); 
?> 
