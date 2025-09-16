<?php
// Registration System for Vult SaaS
session_start();

// Database connection
$servername = "database";
$username = "root";
$password = "root";
$vultDb = "vult";

$vultConn = new PDO("mysql:host=$servername;dbname=$vultDb;charset=utf8mb4", $username, $password);
$vultConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$error = '';
$success = '';

// Handle registration
if ($_POST) {
    $academyName = $_POST['academy_name'] ?? '';
    $managerName = $_POST['manager_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $branchesCount = $_POST['branches_count'] ?? 1;
    $sports = $_POST['sports'] ?? [];
    $description = $_POST['description'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if ($academyName && $managerName && $email && $phone && $password && $confirmPassword) {
        if ($password !== $confirmPassword) {
            $error = 'كلمات المرور غير متطابقة';
        } else {
            try {
                // Check if email already exists
                $stmt = $vultConn->prepare("SELECT id FROM user WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'البريد الإلكتروني مستخدم بالفعل';
                } else {
                    // Insert academy request
                    $stmt = $vultConn->prepare("INSERT INTO academy_requests 
                        (academy_name, manager_name, email, phone, city, branches_count, sports, description, status, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
                    $stmt->execute([
                        $academyName, $managerName, $email, $phone, $city, $branchesCount, 
                        implode(',', $sports), $description
                    ]);
                    
                    $requestId = $vultConn->lastInsertId();
                    
                    // Create user account
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $vultConn->prepare("INSERT INTO user 
                        (username, email, password_hash, auth_key, access_token, created_at, updated_at, status, user_type, trial_started_at, trial_expires_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1, 'academy_admin', ?, ?)");
                    
                    $authKey = bin2hex(random_bytes(16));
                    $accessToken = bin2hex(random_bytes(20));
                    $currentTime = time();
                    $trialExpires = $currentTime + (7 * 24 * 60 * 60); // 7 days
                    
                    $stmt->execute([
                        $email, $email, $passwordHash, $authKey, $accessToken, 
                        $currentTime, $currentTime, $currentTime, $trialExpires
                    ]);
                    
                    $userId = $vultConn->lastInsertId();
                    
                    // Update academy request with user ID
                    $stmt = $vultConn->prepare("UPDATE academy_requests SET user_id = ? WHERE id = ?");
                    $stmt->execute([$userId, $requestId]);
                    
                    $success = 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.';
                }
            } catch (PDOException $e) {
                $error = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
            }
        }
    } else {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل جديد - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .register-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 20px 0;
        }
        
        .register-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
        }
        
        .sports-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            font-weight: 500;
        }
        
        .platform-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }
        
        .sport-checkbox {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .sport-checkbox:hover {
            background: #e9ecef;
        }
        
        .sport-checkbox.selected {
            background: #ff6b35;
            color: white;
            border-color: #ff6b35;
        }
        
        .sport-checkbox input[type="checkbox"] {
            display: none;
        }
    </style>
</head>
<body>
    <div class="register-section">
        <div class="platform-badge">
            <i class="fas fa-globe me-2"></i>
            Vult SaaS Platform
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="register-card p-5">
                        <div class="text-center mb-4">
                            <div class="sports-icon">
                                <i class="fas fa-futbol text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-3">انضم إلى Vult</h2>
                            <p class="text-muted fs-5">سجل أكاديميتك وابدأ تجربتك المجانية</p>
                        </div>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= $error ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $success ?>
                            <div class="mt-3">
                                <a href="/sign-in/login.php" class="btn btn-primary">تسجيل الدخول</a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="academy_name" class="form-label fw-bold text-dark">اسم الأكاديمية *</label>
                                        <input type="text" class="form-control" id="academy_name" name="academy_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="manager_name" class="form-label fw-bold text-dark">اسم المدير *</label>
                                        <input type="text" class="form-control" id="manager_name" name="manager_name" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold text-dark">البريد الإلكتروني *</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-bold text-dark">رقم الهاتف *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label fw-bold text-dark">المدينة *</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="branches_count" class="form-label fw-bold text-dark">عدد الفروع</label>
                                        <input type="number" class="form-control" id="branches_count" name="branches_count" value="1" min="1">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">الرياضات المقدمة *</label>
                                <div class="row" id="sportsContainer">
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="كرة القدم">
                                            <i class="fas fa-futbol me-2"></i>كرة القدم
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="كرة السلة">
                                            <i class="fas fa-basketball-ball me-2"></i>كرة السلة
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="كرة الطائرة">
                                            <i class="fas fa-volleyball-ball me-2"></i>كرة الطائرة
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="التنس">
                                            <i class="fas fa-table-tennis me-2"></i>التنس
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="السباحة">
                                            <i class="fas fa-swimmer me-2"></i>السباحة
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="الجمباز">
                                            <i class="fas fa-dumbbell me-2"></i>الجمباز
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="الرياضات القتالية">
                                            <i class="fas fa-fist-raised me-2"></i>الرياضات القتالية
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="sport-checkbox" onclick="toggleSport(this)">
                                            <input type="checkbox" name="sports[]" value="أخرى">
                                            <i class="fas fa-plus me-2"></i>أخرى
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold text-dark">وصف الأكاديمية</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-bold text-dark">كلمة المرور *</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label fw-bold text-dark">تأكيد كلمة المرور *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-register text-white">
                                    تسجيل الأكاديمية
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p class="text-muted">لديك حساب بالفعل؟ 
                                    <a href="/sign-in/login.php" class="text-decoration-none fw-bold" style="color: #ff6b35;">سجل الدخول</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSport(element) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                element.classList.add('selected');
            } else {
                element.classList.remove('selected');
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const sports = document.querySelectorAll('input[name="sports[]"]:checked');
            if (sports.length === 0) {
                e.preventDefault();
                alert('يرجى اختيار رياضة واحدة على الأقل');
            }
        });
    </script>
</body>
</html>
