<?php 
$conn = mysqli_connect("localhost","root","","class_db");  

// VERY DANGEROUS PROBLEM: The `id` value from the URL is directly inserted into the SQL query.
// This is dangerous because a user can manipulate the URL (e.g., ?id=0 OR 1=1)
// and delete ALL records in the students table.
//  Validate the input and use a prepared statement or cast the value to an integer before using it in the SQL query.
$sql = "DELETE FROM students WHERE id = " . $_GET['id']; 

//  Use intval() to ensure the value is numeric, or use a prepared statement
// to safely bind the parameter before executing the query

mysqli_query($conn, $sql); 
?> 
