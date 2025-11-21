<?php 
// The ID column is an integer, but the query uses quotes around $id.
// This works but can be inefficient and may prevent proper use of the database index.
// Remove quotes or cast $id to int to ensure proper indexing and efficiency.
$id = $_GET['id']; 
$sql = "SELECT * FROM students WHERE id = '$id'"; 
?> 
