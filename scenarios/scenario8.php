<?php 
$conn = mysqli_connect("localhost","root","","class_db"); 

$res = mysqli_query($conn,"SELECT * FROM students"); 

$row = mysqli_fetch_assoc($res); 

//  mysqli_fetch_assoc() is called only once,
// so only the first student's email is printed.
// the solution to this: Use a while loop to fetch and display all rows.

echo $row['email']; // THIS prints first only
?> 
