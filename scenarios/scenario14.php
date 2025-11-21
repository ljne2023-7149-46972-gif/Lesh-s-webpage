<?php 
//  the problems here being Array elements are not properly quoted, causing SQL syntax errors.
//  Using $data[first_name] without quotes can also cause "undefined index" if the key is missing.
// the solution is to: Use proper indexing with quotes, e.g., $data['first_name'], and wrap string values in single quotes.
$data = $_POST; 

$sql = "INSERT INTO students (first_name, last_name, email) VALUES ($data[first_name], $data[last_name], $data[email])"; 
?> 