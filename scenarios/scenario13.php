<?php 
//  The UPDATE query lacks a WHERE clause, so it updates all rows in the table.
//  Add a WHERE clause to target a specific record, e.g., WHERE id = ?
$newEmail = $_POST['email']; 
$sql = "UPDATE students SET email='$newEmail'"; 
mysqli_query($conn,$sql); 
?> 
