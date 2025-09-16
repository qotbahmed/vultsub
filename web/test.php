<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
echo "PHP is working!<br>";
echo "Current directory: " . getcwd() . "<br>";
echo "File exists: " . (file_exists("frontend/index.php") ? "Yes" : "No") . "<br>";
echo "File exists: " . (file_exists("academy-simple.php") ? "Yes" : "No") . "<br>";
?>
