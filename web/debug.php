<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

echo "Debug Test<br>";
echo "Current directory: " . getcwd() . "<br>";
echo "File exists frontend/index.php: " . (file_exists("frontend/index.php") ? "Yes" : "No") . "<br>";
echo "File exists academy-simple.php: " . (file_exists("academy-simple.php") ? "Yes" : "No") . "<br>";

$subdomain = $_GET['subdomain'] ?? 'main';
echo "Subdomain: " . $subdomain . "<br>";

if ($subdomain === 'main' || $subdomain === 'app') {
    echo "Trying to include frontend/index.php<br>";
    if (file_exists("frontend/index.php")) {
        include 'frontend/index.php';
    } else {
        echo "File not found!<br>";
    }
} elseif ($subdomain === 'academy') {
    echo "Trying to include academy-simple.php<br>";
    if (file_exists("academy-simple.php")) {
        include 'academy-simple.php';
    } else {
        echo "File not found!<br>";
    }
}
?>
