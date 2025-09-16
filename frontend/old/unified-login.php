<?php
// Unified Login System
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connections
$vultDb = new PDO("mysql:host=database;dbname=vult;charset=utf8mb4", "root", "root");
$vultDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$portalDb = new PDO("mysql:host=database;dbname=portal;charset=utf8mb4", "root", "root");
$portalDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    
    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required']);
        exit;
    }
    
    try {
        // Check user in Vult database
        $stmt = $vultDb->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $vultUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check user in Portal database
        $stmt = $portalDb->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $portalUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $user = null;
        $database = '';
        
        // Determine which database has the user
        if ($vultUser && password_verify($password, $vultUser['password_hash'])) {
            $user = $vultUser;
            $database = 'vult';
        } elseif ($portalUser && password_verify($password, $portalUser['password_hash'])) {
            $user = $portalUser;
            $database = 'portal';
        }
        
        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            exit;
        }
        
        // Check if user is active
        if ($user['status'] != 1) {
            http_response_code(403);
            echo json_encode(['error' => 'Account is inactive']);
            exit;
        }
        
        // Check trial status if applicable
        $trialActive = false;
        $trialDaysLeft = 0;
        
        if ($user['trial_expires_at']) {
            $trialActive = $user['trial_expires_at'] > time();
            $trialDaysLeft = $trialActive ? max(0, ceil(($user['trial_expires_at'] - time()) / (24 * 60 * 60))) : 0;
        }
        
        // Determine redirect URL based on user type and trial status
        $redirectUrl = '';
        
        if ($database === 'vult') {
            if ($trialActive) {
                $redirectUrl = 'http://vult-saas.localhost/trial-dashboard/?email=' . $email;
            } else {
                $redirectUrl = 'http://vult-saas.localhost/?subdomain=pricing';
            }
        } else {
            // Portal user
            if ($user['academy_id']) {
                // Check academy subscription status
                $stmt = $portalDb->prepare("SELECT subscription_status, subscription_plan FROM academies WHERE id = ?");
                $stmt->execute([$user['academy_id']]);
                $academy = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($academy && $academy['subscription_status'] === 'active') {
                    $redirectUrl = 'http://portal.localhost/academies';
                } else {
                    $redirectUrl = 'http://vult-saas.localhost/?subdomain=pricing';
                }
            } else {
                $redirectUrl = 'http://portal.localhost/';
            }
        }
        
        // Generate session token
        $sessionToken = bin2hex(random_bytes(32));
        
        // Store session in database
        $sessionSql = "INSERT INTO user_sessions (user_id, token, database, created_at, expires_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $vultDb->prepare($sessionSql);
        $stmt->execute([
            $user['id'],
            $sessionToken,
            $database,
            time(),
            time() + (24 * 60 * 60) // 24 hours
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'database' => $database,
                'academy_id' => $user['academy_id'] ?? null,
                'trial_active' => $trialActive,
                'trial_days_left' => $trialDaysLeft,
                'redirect_url' => $redirectUrl,
                'session_token' => $sessionToken
            ]
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
