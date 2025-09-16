<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$host = 'database';
$dbname = 'vult';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

switch ($method) {
    case 'POST':
        if ($endpoint === 'start-trial') {
            startTrial($pdo);
        } elseif ($endpoint === 'check-trial') {
            checkTrialStatus($pdo);
        } elseif ($endpoint === 'approve-academy') {
            approveAcademy($pdo);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        
    case 'GET':
        if ($endpoint === 'trial-status') {
            getTrialStatus($pdo);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

function startTrial($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Convert sports array to string
        $sports = '';
        if (isset($input['sports']) && is_array($input['sports'])) {
            $sports = implode(',', $input['sports']);
        }
        
        // 1. إنشاء طلب أكاديمية
        $sql = "INSERT INTO academy_requests (academy_name, manager_name, email, phone, address, city, branches_count, sports, description, status) 
                VALUES (:academy_name, :manager_name, :email, :phone, :address, :city, :branches_count, :sports, :description, 'pending')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':academy_name' => $input['academy_name'],
            ':manager_name' => $input['manager_name'],
            ':email' => $input['email'],
            ':phone' => $input['phone'],
            ':address' => $input['address'] ?? '',
            ':city' => $input['city'] ?? '',
            ':branches_count' => $input['branches_count'] ?? 1,
            ':sports' => $sports,
            ':description' => $input['description'] ?? ''
        ]);
        
        $requestId = $pdo->lastInsertId();
        
        // 2. إنشاء حساب مستخدم مؤقت
        $trialStart = time();
        $trialEnd = $trialStart + (7 * 24 * 60 * 60); // 7 أيام
        $currentTime = time();
        
        $sql = "INSERT INTO user (username, email, trial_started_at, trial_expires_at, academy_id, status, created_at, updated_at, auth_key, access_token, password_hash) 
                VALUES (:username, :email, :trial_started_at, :trial_expires_at, :academy_id, 1, :created_at, :updated_at, :auth_key, :access_token, :password_hash)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $input['email'],
            ':email' => $input['email'],
            ':trial_started_at' => $trialStart,
            ':trial_expires_at' => $trialEnd,
            ':academy_id' => $requestId, // ربط مؤقت
            ':created_at' => $currentTime,
            ':updated_at' => $currentTime,
            ':auth_key' => bin2hex(random_bytes(16)),
            ':access_token' => bin2hex(random_bytes(20)),
            ':password_hash' => password_hash('trial123', PASSWORD_DEFAULT)
        ]);
        
        $userId = $pdo->lastInsertId();
        
        // 3. إرسال إيميل ترحيبي
        sendWelcomeEmail($input['email'], $input['academy_name'], $trialEnd);
        
        echo json_encode([
            'success' => true,
            'message' => 'تم بدء التجربة المجانية بنجاح',
            'data' => [
                'request_id' => $requestId,
                'user_id' => $userId,
                'trial_start' => $trialStart,
                'trial_end' => $trialEnd,
                'trial_days_left' => 7
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function checkTrialStatus($pdo) {
    try {
        $email = $_GET['email'] ?? '';
        
        if (!$email) {
            http_response_code(400);
            echo json_encode(['error' => 'Email is required']);
            return;
        }
        
        $sql = "SELECT u.*, ar.academy_name, ar.status as request_status 
                FROM user u 
                LEFT JOIN academy_requests ar ON u.academy_id = ar.id 
                WHERE u.email = :email";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        
        $trialStatus = [
            'is_trial_active' => $user['trial_expires_at'] && $user['trial_expires_at'] > time(),
            'trial_days_left' => $user['trial_expires_at'] ? max(0, ceil(($user['trial_expires_at'] - time()) / (24 * 60 * 60))) : 0,
            'trial_start' => $user['trial_started_at'],
            'trial_end' => $user['trial_expires_at'],
            'academy_name' => $user['academy_name'],
            'request_status' => $user['request_status']
        ];
        
        echo json_encode([
            'success' => true,
            'data' => $trialStatus
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function approveAcademy($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $requestId = $input['request_id'] ?? '';
        
        if (!$requestId) {
            http_response_code(400);
            echo json_encode(['error' => 'Request ID is required']);
            return;
        }
        
        // 1. تحديث حالة الطلب
        $sql = "UPDATE academy_requests SET status = 'approved', approved_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $requestId]);
        
        // 2. إنشاء أكاديمية في جدول academies
        $requestSql = "SELECT * FROM academy_requests WHERE id = :id";
        $stmt = $pdo->prepare($requestSql);
        $stmt->execute([':id' => $requestId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            http_response_code(404);
            echo json_encode(['error' => 'Request not found']);
            return;
        }
        
        // إنشاء أكاديمية في Portal
        $academySql = "INSERT INTO academies (title, contact_email, contact_phone, address, city_id, district_id, 
                     description, manager_id, main, created_by, created_at, updated_at, status, 
                     primary_color, secondary_color, accent_color, sport_icons, days, startTime, endTime) 
                     VALUES (:title, :email, :phone, :address, :city_id, :district_id, :description, 
                     :manager_id, 1, :created_by, :created_at, :updated_at, 1, '#1e3c72', '#ff6b35', 
                     '#2a5298', :sports, '1,2,3,4,5,6,7', '06:00:00', '22:00:00')";
        
        $stmt = $pdo->prepare($academySql);
        $stmt->execute([
            ':title' => $request['academy_name'],
            ':email' => $request['email'],
            ':phone' => $request['phone'],
            ':address' => $request['address'],
            ':city_id' => 1, // افتراضي
            ':district_id' => 1, // افتراضي
            ':description' => $request['description'],
            ':manager_id' => 1, // سيتم تحديثه لاحقاً
            ':created_by' => 1,
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
            ':sports' => $request['sports']
        ]);
        
        $academyId = $pdo->lastInsertId();
        
        // 3. تحديث academy_id في جدول user
        $updateUserSql = "UPDATE user SET academy_id = ? WHERE academy_id = ?";
        $stmt = $pdo->prepare($updateUserSql);
        $stmt->execute([
            ':academy_id' => $academyId,
            ':request_id' => $requestId
        ]);
        
        // 4. إرسال إيميل موافقة
        sendApprovalEmail($request['email'], $request['academy_name'], $academyId);
        
        echo json_encode([
            'success' => true,
            'message' => 'تم الموافقة على الأكاديمية وإنشاؤها في النظام',
            'data' => [
                'academy_id' => $academyId,
                'academy_name' => $request['academy_name']
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function getTrialStatus($pdo) {
    try {
        $sql = "SELECT 
                    COUNT(CASE WHEN ar.status = 'pending' THEN 1 END) as pending_requests,
                    COUNT(CASE WHEN ar.status = 'approved' THEN 1 END) as approved_requests,
                    COUNT(CASE WHEN ar.status = 'rejected' THEN 1 END) as rejected_requests,
                    COUNT(CASE WHEN u.trial_expires_at > UNIX_TIMESTAMP() THEN 1 END) as active_trials,
                    COUNT(CASE WHEN u.trial_expires_at <= UNIX_TIMESTAMP() AND u.trial_expires_at > 0 THEN 1 END) as expired_trials
                FROM academy_requests ar
                LEFT JOIN user u ON ar.id = u.academy_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function sendWelcomeEmail($email, $academyName, $trialEnd) {
    // في التطبيق الحقيقي، هنا ستستخدم SwiftMailer أو مكتبة إيميل أخرى
    $subject = "مرحباً بك في Vult - بدء تجربتك المجانية";
    $message = "
    <h2>مرحباً بك في Vult!</h2>
    <p>أهلاً وسهلاً بك في منصة Vult لإدارة الأكاديميات الرياضية.</p>
    <p><strong>اسم الأكاديمية:</strong> $academyName</p>
    <p><strong>تجربتك المجانية تنتهي في:</strong> " . date('Y-m-d H:i:s', $trialEnd) . "</p>
    <p>يمكنك الآن الوصول إلى لوحة التحكم والبدء في إدارة أكاديميتك.</p>
    <p>رابط الدخول: <a href='http://vult-saas.localhost/?subdomain=academy'>لوحة التحكم</a></p>
    ";
    
    // Log the email (في التطبيق الحقيقي، أرسل الإيميل فعلياً)
    error_log("Welcome email sent to: $email");
}

function sendApprovalEmail($email, $academyName, $academyId) {
    $subject = "تمت الموافقة على طلبك - Vult";
    $message = "
    <h2>مبروك! تمت الموافقة على طلبك</h2>
    <p>تمت الموافقة على طلب انضمام أكاديميتك <strong>$academyName</strong> إلى منصة Vult.</p>
    <p>يمكنك الآن الوصول إلى النظام الكامل وإدارة أكاديميتك.</p>
    <p>رابط الدخول: <a href='http://vult-saas.localhost/?subdomain=academy'>لوحة التحكم</a></p>
    ";
    
    error_log("Approval email sent to: $email");
}
?>
