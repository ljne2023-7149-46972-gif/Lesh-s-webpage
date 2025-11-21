<?php 
//  The SQL query uses $aeg, which is undefined. The correct variable is $age.
// the solution being the Change of $aeg to $age to match the posted value from $_POST['age']
$age = $_POST['age']; 
$sql = "SELECT * FROM students WHERE age = $aeg"; // ALWAYS check your spelling right 
?> 
