<?php
/**
 * Simple migration runner for Vult SaaS Platform
 */

// Database configuration
$host = 'database';
$dbname = 'vult';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check if academy_requests table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'academy_requests'");
    if ($stmt->rowCount() == 0) {
        echo "Creating academy_requests table...\n";
        
        $sql = "CREATE TABLE `academy_requests` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `academy_name` varchar(255) NOT NULL,
            `manager_name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `phone` varchar(20) NOT NULL,
            `address` text,
            `city` varchar(100) DEFAULT NULL,
            `branches_count` int(11) DEFAULT 1,
            `sports` text,
            `description` text,
            `status` enum('pending','approved','rejected') DEFAULT 'pending',
            `requested_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `approved_at` timestamp NULL DEFAULT NULL,
            `rejected_at` timestamp NULL DEFAULT NULL,
            `notes` text,
            `created_by` int(11) DEFAULT NULL,
            `updated_by` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_status` (`status`),
            KEY `idx_requested_at` (`requested_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "academy_requests table created successfully!\n";
        
        // Insert sample data
        $sampleData = [
            [
                'academy_name' => 'أكاديمية النجوم الرياضية',
                'manager_name' => 'أحمد محمد العلي',
                'email' => 'ahmed@stars-sports.com',
                'phone' => '+966501234567',
                'address' => 'شارع الملك فهد، الرياض',
                'city' => 'الرياض',
                'branches_count' => 3,
                'sports' => 'كرة القدم,كرة السلة,السباحة',
                'description' => 'أكاديمية متخصصة في تدريب الشباب على الرياضات المختلفة',
                'status' => 'pending'
            ],
            [
                'academy_name' => 'نادي الأبطال الرياضي',
                'manager_name' => 'فاطمة أحمد السعيد',
                'email' => 'fatima@champions-club.com',
                'phone' => '+966507654321',
                'address' => 'شارع التحلية، جدة',
                'city' => 'جدة',
                'branches_count' => 2,
                'sports' => 'كرة القدم,الجمباز',
                'description' => 'نادي رياضي يهدف لتنمية المواهب الشابة',
                'status' => 'pending'
            ],
            [
                'academy_name' => 'مركز التميز الرياضي',
                'manager_name' => 'محمد عبدالله القحطاني',
                'email' => 'mohammed@excellence-sports.com',
                'phone' => '+966503456789',
                'address' => 'شارع الكورنيش، الدمام',
                'city' => 'الدمام',
                'branches_count' => 5,
                'sports' => 'كرة القدم,كرة السلة,التنس,السباحة',
                'description' => 'مركز متطور لتدريب الرياضيين المحترفين',
                'status' => 'approved'
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO academy_requests (academy_name, manager_name, email, phone, address, city, branches_count, sports, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($sampleData as $data) {
            $stmt->execute([
                $data['academy_name'],
                $data['manager_name'],
                $data['email'],
                $data['phone'],
                $data['address'],
                $data['city'],
                $data['branches_count'],
                $data['sports'],
                $data['description'],
                $data['status']
            ]);
        }
        
        echo "Sample data inserted successfully!\n";
    } else {
        echo "academy_requests table already exists!\n";
    }
    
    // Check if players table has the required columns
    $stmt = $pdo->query("SHOW COLUMNS FROM players LIKE 'status'");
    if ($stmt->rowCount() == 0) {
        echo "Adding status column to players table...\n";
        $pdo->exec("ALTER TABLE players ADD COLUMN status ENUM('active','inactive','suspended') DEFAULT 'active' AFTER parent_id");
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM players LIKE 'sport'");
    if ($stmt->rowCount() == 0) {
        echo "Adding sport column to players table...\n";
        $pdo->exec("ALTER TABLE players ADD COLUMN sport VARCHAR(100) DEFAULT NULL AFTER status");
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM players LIKE 'attendance_rate'");
    if ($stmt->rowCount() == 0) {
        echo "Adding attendance_rate column to players table...\n";
        $pdo->exec("ALTER TABLE players ADD COLUMN attendance_rate DECIMAL(5,2) DEFAULT 0.00 AFTER sport");
    }
    
    // Check if user table has trial columns
    $stmt = $pdo->query("SHOW COLUMNS FROM user LIKE 'trial_started_at'");
    if ($stmt->rowCount() == 0) {
        echo "Adding trial columns to user table...\n";
        $pdo->exec("ALTER TABLE user ADD COLUMN trial_started_at INT NULL AFTER updated_at");
        $pdo->exec("ALTER TABLE user ADD COLUMN trial_expires_at INT NULL AFTER trial_started_at");
        $pdo->exec("ALTER TABLE user ADD COLUMN academy_id INT NULL AFTER trial_expires_at");
    }
    
    echo "All migrations completed successfully!\n";
    
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
