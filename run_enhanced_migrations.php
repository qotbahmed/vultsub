<?php
// Enhanced Database Migrations
$servername = "database";
$username = "root";
$password = "root";
$vultDb = "vult";
$portalDb = "portal";

try {
    // Connect to Vult database
    $vultConn = new PDO("mysql:host=$servername;dbname=$vultDb;charset=utf8mb4", $username, $password);
    $vultConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to Vult database successfully!\n";
    
    // Connect to Portal database
    $portalConn = new PDO("mysql:host=$servername;dbname=$portalDb;charset=utf8mb4", $username, $password);
    $portalConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to Portal database successfully!\n";
    
    // 1. Update academy_requests table in Vult
    echo "Updating academy_requests table...\n";
    try {
        $sql = "ALTER TABLE academy_requests ADD COLUMN portal_academy_id INT NULL";
        $vultConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column portal_academy_id already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academy_requests ADD COLUMN portal_user_id INT NULL";
        $vultConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column portal_user_id already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academy_requests ADD COLUMN approved_at DATETIME NULL";
        $vultConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column approved_at already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academy_requests ADD COLUMN rejected_at DATETIME NULL";
        $vultConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column rejected_at already exists or error: " . $e->getMessage() . "\n";
    }
    
    echo "academy_requests table updated!\n";
    
    // 2. Create user_sessions table in Vult
    echo "Creating user_sessions table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS user_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(64) NOT NULL,
        target_database VARCHAR(20) NOT NULL,
        created_at INT NOT NULL,
        expires_at INT NOT NULL,
        INDEX idx_token (token),
        INDEX idx_user_id (user_id)
    )";
    $vultConn->exec($sql);
    echo "user_sessions table created!\n";
    
    // 3. Update academies table in Portal
    echo "Updating academies table in Portal...\n";
    try {
        $sql = "ALTER TABLE academies ADD COLUMN vult_request_id INT NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column vult_request_id already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN subscription_plan VARCHAR(50) DEFAULT 'trial'";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column subscription_plan already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN subscription_status VARCHAR(50) DEFAULT 'active'";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column subscription_status already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN subscription_start DATETIME NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column subscription_start already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN subscription_end DATETIME NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column subscription_end already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN trial_start INT NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column trial_start already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN trial_end INT NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column trial_end already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN trial_status VARCHAR(50) DEFAULT 'active'";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column trial_status already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE academies ADD COLUMN monthly_revenue DECIMAL(10,2) DEFAULT 0.00";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column monthly_revenue already exists or error: " . $e->getMessage() . "\n";
    }
    
    echo "academies table updated!\n";
    
    // 4. Update user table in Portal
    echo "Updating user table in Portal...\n";
    try {
        $sql = "ALTER TABLE user ADD COLUMN trial_started_at INT NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column trial_started_at already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE user ADD COLUMN trial_expires_at INT NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column trial_expires_at already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE user ADD COLUMN subscription_plan VARCHAR(50) NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column subscription_plan already exists or error: " . $e->getMessage() . "\n";
    }
    
    try {
        $sql = "ALTER TABLE user ADD COLUMN subscription_status VARCHAR(50) NULL";
        $portalConn->exec($sql);
    } catch (PDOException $e) {
        echo "Column subscription_status already exists or error: " . $e->getMessage() . "\n";
    }
    
    echo "user table updated!\n";
    
    // 5. Create business_analytics table
    echo "Creating business_analytics table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS business_analytics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        total_requests INT DEFAULT 0,
        approved_requests INT DEFAULT 0,
        total_academies INT DEFAULT 0,
        active_academies INT DEFAULT 0,
        trial_academies INT DEFAULT 0,
        basic_academies INT DEFAULT 0,
        premium_academies INT DEFAULT 0,
        enterprise_academies INT DEFAULT 0,
        monthly_revenue DECIMAL(10,2) DEFAULT 0.00,
        conversion_rate DECIMAL(5,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_date (date)
    )";
    $portalConn->exec($sql);
    echo "business_analytics table created!\n";
    
    // 6. Create subscription_history table
    echo "Creating subscription_history table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS subscription_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        academy_id INT NOT NULL,
        old_plan VARCHAR(50) NULL,
        new_plan VARCHAR(50) NOT NULL,
        old_status VARCHAR(50) NULL,
        new_status VARCHAR(50) NOT NULL,
        changed_by INT NULL,
        change_reason TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_academy_id (academy_id),
        INDEX idx_created_at (created_at)
    )";
    $portalConn->exec($sql);
    echo "subscription_history table created!\n";
    
    // 7. Create trial_events table
    echo "Creating trial_events table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS trial_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        academy_id INT NOT NULL,
        user_id INT NOT NULL,
        event_type VARCHAR(50) NOT NULL,
        event_data TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_academy_id (academy_id),
        INDEX idx_user_id (user_id),
        INDEX idx_event_type (event_type)
    )";
    $portalConn->exec($sql);
    echo "trial_events table created!\n";
    
    echo "\n✅ All enhanced migrations completed successfully!\n";
    echo "Database structure updated with:\n";
    echo "- Enhanced academy_requests table\n";
    echo "- User sessions management\n";
    echo "- Subscription management\n";
    echo "- Business analytics\n";
    echo "- Trial events tracking\n";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
