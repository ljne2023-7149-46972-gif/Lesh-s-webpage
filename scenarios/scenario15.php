<?php 
$page = $_GET['page']; 
$limit = 5; 

// ⚠️ PROBLEM: Using $page directly from user input without validation can cause very large offsets
// and potentially crash MySQL if ?page=1000000000
//  $page comes directly from user input, allowing extremely large numbers
// which can crash MySQL when used in LIMIT/OFFSET.
//  Validate $page to be a positive integer and restrict it to a reasonable maximum value.
$offset = $page * $limit; 

$sql = "SELECT * FROM students LIMIT $offset, $limit"; 
?> 
