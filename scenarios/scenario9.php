<?php 
// THIS ONE $_POST['id'] is used, but the link sends data via GET
// Use $_GET['id'] instead to match the link
// This causes an "undefined index" error because POST and GET are different methods of sending data. 
$id = $_POST['id']; 
?> 
<a href="view.php?id=3">View Student</a> 
