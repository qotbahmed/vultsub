<?php
// Simple test to check if the academy system works
echo "<h1>Vult Academy System Test</h1>";

// Test database connection
try {
    $pdo = new PDO('mysql:host=database;port=3306;dbname=vult', 'root', 'root');
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test if we can query the users table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM user");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color: green;'>✓ Users table accessible. Count: " . $result['count'] . "</p>";
    
    // Test if we can query the academies table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM academies");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color: green;'>✓ Academies table accessible. Count: " . $result['count'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test if we can include the Yii2 framework
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<p style='color: green;'>✓ Composer autoload found</p>";
} else {
    echo "<p style='color: red;'>✗ Composer autoload not found</p>";
}

// Test if we can include the User model
if (file_exists(__DIR__ . '/../common/models/User.php')) {
    echo "<p style='color: green;'>✓ User model found</p>";
} else {
    echo "<p style='color: red;'>✗ User model not found</p>";
}

echo "<hr>";
echo "<h2>Quick Links:</h2>";
echo "<p><a href='?subdomain=academy'>Academy Dashboard</a></p>";
echo "<p><a href='?subdomain=signup'>Trial Signup</a></p>";
echo "<p><a href='?subdomain=pricing'>Pricing Plans</a></p>";
echo "<p><a href='?subdomain=main'>Main Landing Page</a></p>";
?>
