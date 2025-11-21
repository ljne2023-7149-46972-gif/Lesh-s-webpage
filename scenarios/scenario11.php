<?php 
// The form uses method="GET", but the PHP code reads $_POST['email']
// This causes "undefined index" because POST and GET are different request methods.
// Either change the form method to POST or use $_GET['email'] in PHP to match the form.
$email = $_POST['email'];  
?> 
<form method="GET" action="save.php"> 
    <input name="email"> 
</form> 
