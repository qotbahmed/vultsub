<?php
// Portal Integration API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database connections
$vultDb = new PDO("mysql:host=database;dbname=vult;charset=utf8mb4", "root", "root");
$vultDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$portalDb = new PDO("mysql:host=database;dbname=portal;charset=utf8mb4", "root", "root");
$portalDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

switch ($method) {
    case 'POST':
        if ($endpoint === 'create-academy') {
            createAcademyInPortal($vultDb, $portalDb);
        } elseif ($endpoint === 'sync-user') {
            syncUserToPortal($vultDb, $portalDb);
        } elseif ($endpoint === 'update-subscription') {
            updateSubscription($vultDb, $portalDb);
        }
        break;
        
    case 'GET':
        if ($endpoint === 'academy-status') {
            getAcademyStatus($vultDb, $portalDb);
        } elseif ($endpoint === 'business-analytics') {
            getBusinessAnalytics($vultDb, $portalDb);
        }
        break;
}

function createAcademyInPortal($vultDb, $portalDb) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $requestId = $input['request_id'] ?? '';
        
        if (!$requestId) {
            http_response_code(400);
            echo json_encode(['error' => 'Request ID is required']);
            return;
        }
        
        // Get request details from Vult
        $stmt = $vultDb->prepare("SELECT * FROM academy_requests WHERE id = ?");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            http_response_code(404);
            echo json_encode(['error' => 'Request not found']);
            return;
        }
        
        // Create academy in Portal database
        $academySql = "INSERT INTO academies (
            title, contact_email, contact_phone, address, city_id, district_id, 
            description, manager_id, main, created_by, created_at, updated_at, 
            status, primary_color, secondary_color, accent_color, sport_icons, 
            days, startTime, endTime, vult_request_id, subscription_plan, 
            subscription_status, trial_start, trial_end
        ) VALUES (
            :title, :email, :phone, :address, :city_id, :district_id, 
            :description, :manager_id, 1, :created_by, :created_at, :updated_at, 
            1, '#1e3c72', '#ff6b35', '#2a5298', :sports, 
            '1,2,3,4,5,6,7', '06:00:00', '22:00:00', :vult_request_id, 
            'trial', 'active', :trial_start, :trial_end
        )";
        
        $currentTime = date('Y-m-d H:i:s');
        $trialStart = time();
        $trialEnd = $trialStart + (7 * 24 * 60 * 60);
        
        $stmt = $portalDb->prepare($academySql);
        $stmt->execute([
            ':title' => $request['academy_name'],
            ':email' => $request['email'],
            ':phone' => $request['phone'],
            ':address' => $request['address'],
            ':city_id' => 1,
            ':district_id' => 1,
            ':description' => $request['description'],
            ':manager_id' => 1,
            ':created_by' => 1,
            ':created_at' => $currentTime,
            ':updated_at' => $currentTime,
            ':sports' => $request['sports'],
            ':vult_request_id' => $requestId,
            ':trial_start' => $trialStart,
            ':trial_end' => $trialEnd
        ]);
        
        $academyId = $portalDb->lastInsertId();
        
        // Create user in Portal
        $userSql = "INSERT INTO user (
            username, email, password_hash, auth_key, access_token, 
            created_at, updated_at, status, user_type, academy_id, 
            trial_started_at, trial_expires_at
        ) VALUES (
            :username, :email, :password_hash, :auth_key, :access_token,
            :created_at, :updated_at, 1, 'academy_admin', :academy_id,
            :trial_start, :trial_end
        )";
        
        $stmt = $portalDb->prepare($userSql);
        $stmt->execute([
            ':username' => $request['email'],
            ':email' => $request['email'],
            ':password_hash' => password_hash('trial123', PASSWORD_DEFAULT),
            ':auth_key' => bin2hex(random_bytes(16)),
            ':access_token' => bin2hex(random_bytes(20)),
            ':created_at' => $currentTime,
            ':updated_at' => $currentTime,
            ':academy_id' => $academyId,
            ':trial_start' => $trialStart,
            ':trial_end' => $trialEnd
        ]);
        
        $userId = $portalDb->lastInsertId();
        
        // Update Vult database with Portal academy ID
        $updateSql = "UPDATE academy_requests SET 
            portal_academy_id = :academy_id, 
            portal_user_id = :user_id,
            status = 'approved',
            approved_at = NOW()
            WHERE id = :request_id";
        
        $stmt = $vultDb->prepare($updateSql);
        $stmt->execute([
            ':academy_id' => $academyId,
            ':user_id' => $userId,
            ':request_id' => $requestId
        ]);
        
        // Update user in Vult with Portal academy ID
        $updateUserSql = "UPDATE user SET academy_id = :academy_id WHERE email = :email";
        $stmt = $vultDb->prepare($updateUserSql);
        $stmt->execute([
            ':academy_id' => $academyId,
            ':email' => $request['email']
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'تم إنشاء الأكاديمية في Portal بنجاح',
            'data' => [
                'academy_id' => $academyId,
                'user_id' => $userId,
                'academy_name' => $request['academy_name'],
                'portal_url' => 'http://portal.localhost/sign-in/login'
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function syncUserToPortal($vultDb, $portalDb) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        
        if (!$email) {
            http_response_code(400);
            echo json_encode(['error' => 'Email is required']);
            return;
        }
        
        // Get user from Vult
        $stmt = $vultDb->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $vultUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$vultUser) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found in Vult']);
            return;
        }
        
        // Check if user exists in Portal
        $stmt = $portalDb->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $portalUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($portalUser) {
            // Update existing user
            $updateSql = "UPDATE user SET 
                trial_started_at = :trial_start,
                trial_expires_at = :trial_end,
                academy_id = :academy_id,
                status = :status
                WHERE email = :email";
            
            $stmt = $portalDb->prepare($updateSql);
            $stmt->execute([
                ':trial_start' => $vultUser['trial_started_at'],
                ':trial_end' => $vultUser['trial_expires_at'],
                ':academy_id' => $vultUser['academy_id'],
                ':status' => $vultUser['status'],
                ':email' => $email
            ]);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'تم مزامنة المستخدم مع Portal',
            'data' => [
                'portal_url' => 'http://portal.localhost/sign-in/login',
                'trial_active' => $vultUser['trial_expires_at'] > time()
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function updateSubscription($vultDb, $portalDb) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $academyId = $input['academy_id'] ?? '';
        $plan = $input['plan'] ?? '';
        $status = $input['status'] ?? '';
        
        if (!$academyId || !$plan) {
            http_response_code(400);
            echo json_encode(['error' => 'Academy ID and plan are required']);
            return;
        }
        
        // Update academy subscription in Portal
        $sql = "UPDATE academies SET 
            subscription_plan = :plan,
            subscription_status = :status,
            subscription_start = :start_date,
            subscription_end = :end_date,
            updated_at = NOW()
            WHERE id = :academy_id";
        
        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime('+1 year'));
        
        $stmt = $portalDb->prepare($sql);
        $stmt->execute([
            ':plan' => $plan,
            ':status' => $status,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':academy_id' => $academyId
        ]);
        
        // Update user status
        $userSql = "UPDATE user SET 
            status = :status,
            updated_at = NOW()
            WHERE academy_id = :academy_id";
        
        $stmt = $portalDb->prepare($userSql);
        $stmt->execute([
            ':status' => $status === 'active' ? 1 : 0,
            ':academy_id' => $academyId
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'تم تحديث الاشتراك بنجاح',
            'data' => [
                'academy_id' => $academyId,
                'plan' => $plan,
                'status' => $status
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function getAcademyStatus($vultDb, $portalDb) {
    try {
        $academyId = $_GET['academy_id'] ?? '';
        
        if (!$academyId) {
            http_response_code(400);
            echo json_encode(['error' => 'Academy ID is required']);
            return;
        }
        
        // Get academy status from Portal
        $stmt = $portalDb->prepare("SELECT 
            id, title, subscription_plan, subscription_status, 
            trial_start, trial_end, created_at, status
            FROM academies WHERE id = ?");
        $stmt->execute([$academyId]);
        $academy = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$academy) {
            http_response_code(404);
            echo json_encode(['error' => 'Academy not found']);
            return;
        }
        
        // Calculate trial status
        $trialActive = $academy['trial_end'] && $academy['trial_end'] > time();
        $trialDaysLeft = $trialActive ? max(0, ceil(($academy['trial_end'] - time()) / (24 * 60 * 60))) : 0;
        
        echo json_encode([
            'success' => true,
            'data' => [
                'academy' => $academy,
                'trial_active' => $trialActive,
                'trial_days_left' => $trialDaysLeft,
                'subscription_active' => $academy['subscription_status'] === 'active'
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function getBusinessAnalytics($vultDb, $portalDb) {
    try {
        // Get comprehensive business analytics
        $analytics = [];
        
        // Vult SaaS Analytics
        $stmt = $vultDb->prepare("SELECT 
            COUNT(*) as total_requests,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_requests,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests
            FROM academy_requests");
        $stmt->execute();
        $analytics['vult_requests'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Portal Analytics
        $stmt = $portalDb->prepare("SELECT 
            COUNT(*) as total_academies,
            SUM(CASE WHEN subscription_plan = 'trial' THEN 1 ELSE 0 END) as trial_academies,
            SUM(CASE WHEN subscription_plan = 'basic' THEN 1 ELSE 0 END) as basic_academies,
            SUM(CASE WHEN subscription_plan = 'premium' THEN 1 ELSE 0 END) as premium_academies,
            SUM(CASE WHEN subscription_plan = 'enterprise' THEN 1 ELSE 0 END) as enterprise_academies,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_academies,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive_academies
            FROM academies");
        $stmt->execute();
        $analytics['portal_academies'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Revenue Analytics
        $stmt = $portalDb->prepare("SELECT 
            subscription_plan,
            COUNT(*) as count,
            CASE 
                WHEN subscription_plan = 'trial' THEN 0
                WHEN subscription_plan = 'basic' THEN 99
                WHEN subscription_plan = 'premium' THEN 199
                WHEN subscription_plan = 'enterprise' THEN 399
                ELSE 0
            END as monthly_revenue
            FROM academies 
            WHERE subscription_status = 'active'
            GROUP BY subscription_plan");
        $stmt->execute();
        $analytics['revenue_breakdown'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate total monthly revenue
        $totalRevenue = 0;
        foreach ($analytics['revenue_breakdown'] as $plan) {
            $totalRevenue += $plan['count'] * $plan['monthly_revenue'];
        }
        $analytics['total_monthly_revenue'] = $totalRevenue;
        
        // Trial Analytics
        $stmt = $portalDb->prepare("SELECT 
            COUNT(*) as total_trials,
            SUM(CASE WHEN trial_end > UNIX_TIMESTAMP() THEN 1 ELSE 0 END) as active_trials,
            SUM(CASE WHEN trial_end <= UNIX_TIMESTAMP() THEN 1 ELSE 0 END) as expired_trials
            FROM academies WHERE subscription_plan = 'trial'");
        $stmt->execute();
        $analytics['trial_stats'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Conversion Rate
        $totalTrials = $analytics['trial_stats']['total_trials'];
        $convertedTrials = $analytics['portal_academies']['basic_academies'] + 
                          $analytics['portal_academies']['premium_academies'] + 
                          $analytics['portal_academies']['enterprise_academies'];
        $analytics['conversion_rate'] = $totalTrials > 0 ? round(($convertedTrials / $totalTrials) * 100, 2) : 0;
        
        echo json_encode([
            'success' => true,
            'data' => $analytics
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
