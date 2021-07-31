<?php
echo "<br>";
print shell_exec('whoami');

echo "<br><br>" . "<h1>The purpose of this form is to add html, php, and jpg files directly from the website to the server. This form will be deleted.</h1>";
$dir = "/var/www/jerryalpa/uploads/";
$filename = $dir . basename($_FILES['Upload']['name']);

$allowed =  array('html', 'txt', 'php');
$ext = pathinfo($_FILES['Upload']['name'], PATHINFO_EXTENSION);

var_dump($_FILES);
echo "<br><br>";
echo "File upload status= " . move_uploaded_file($_FILES['Upload']['tmp_name'], $filename);
echo "<br>File Name: " . $_FILES['Upload']['name'] . "<br>";
echo "File Type: " . $_FILES['Upload']['type'] . "<br>";
echo "File Size: " . $_FILES['Upload']['size'] . "Bytes<br>";


?>
