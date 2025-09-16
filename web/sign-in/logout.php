<?php
// Logout System for Vult SaaS
session_start();

// Destroy session
session_destroy();

// Redirect to login page
header('Location: /sign-in/login.php');
exit;
?>
