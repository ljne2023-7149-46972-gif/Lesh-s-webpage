<?php 
$conn = mysqli_connect("localhost","root","","class_db"); 


$email = $_POST['emial']; //THIS ONE is misspelled gotta keep an eye on em of course 

$sql = "SELECT * FROM students WHERE email='$email'"; 
$res = mysqli_query($conn, $sql); 

//  The form field is supposed to be name="email",
// but the code reads $_POST['emial'], which is misspelled.
// This means the variable will be empty and the query will not work.
// Correct the variable to $_POST['email'] so it matches the form field, always spell right lol
?> 
