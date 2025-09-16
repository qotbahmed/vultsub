<?php
// Main Index for Vult SaaS
$subdomain = $_GET['subdomain'] ?? '';

switch ($subdomain) {
    case 'signup':
        include 'sign-in/register.php';
        break;
    case 'pricing':
        include 'pricing.php';
        break;
    case 'login':
        include 'sign-in/login.php';
        break;
    default:
        include 'home.php';
        break;
}
?>
