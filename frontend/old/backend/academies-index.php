<?php
// Academies Index - Backend for Vult SaaS
require_once '../common/config/main.php';

use yii\web\Application;
use common\models\User;
use common\models\Academies;

$config = require '../common/config/main.php';
$app = new Application($config);

// Check if user is logged in and has admin role
if (Yii::$app->user->isGuest) {
    header('Location: /sign-in/login');
    exit;
}

$user = Yii::$app->user->identity;
$roles = Yii::$app->authManager->getRolesByUser($user->id);
$isAdmin = false;

foreach ($roles as $role) {
    if (in_array($role->name, ['administrator', 'manager'])) {
        $isAdmin = true;
        break;
    }
}

if (!$isAdmin) {
    header('Location: /');
    exit;
}

// Database connections
$vultDb = new PDO("mysql:host=database;dbname=vult;charset=utf8mb4", "root", "root");
$vultDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$portalDb = new PDO("mysql:host=database;dbname=portal;charset=utf8mb4", "root", "root");
$portalDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get academies with subscription info
$academies = [];
$stats = [];

try {
    // Get academies from Portal database
    $stmt = $portalDb->prepare("SELECT 
        a.*, u.email, u.status as user_status,
        CASE 
            WHEN a.subscription_plan = 'trial' THEN 0
            WHEN a.subscription_plan = 'basic' THEN 99
            WHEN a.subscription_plan = 'premium' THEN 199
            WHEN a.subscription_plan = 'enterprise' THEN 399
            ELSE 0
        END as monthly_revenue,
        ar.academy_name as vult_academy_name,
        ar.manager_name,
        ar.phone as vult_phone,
        ar.city as vult_city,
        ar.sports as vult_sports,
        ar.status as request_status,
        ar.created_at as request_created_at
        FROM academies a
        LEFT JOIN user u ON a.id = u.academy_id
        LEFT JOIN academy_requests ar ON a.vult_request_id = ar.id
        ORDER BY a.created_at DESC");
    $stmt->execute();
    $academies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stmt = $portalDb->prepare("SELECT 
        COUNT(*) as total_academies,
        SUM(CASE WHEN subscription_plan = 'trial' THEN 1 ELSE 0 END) as trial_academies,
        SUM(CASE WHEN subscription_plan = 'basic' THEN 1 ELSE 0 END) as basic_academies,
        SUM(CASE WHEN subscription_plan = 'premium' THEN 1 ELSE 0 END) as premium_academies,
        SUM(CASE WHEN subscription_plan = 'enterprise' THEN 1 ELSE 0 END) as enterprise_academies,
        SUM(CASE WHEN subscription_status = 'active' THEN 1 ELSE 0 END) as active_subscriptions,
        SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_academies,
        SUM(monthly_revenue) as total_monthly_revenue
        FROM (
            SELECT *, 
            CASE 
                WHEN subscription_plan = 'trial' THEN 0
                WHEN subscription_plan = 'basic' THEN 99
                WHEN subscription_plan = 'premium' THEN 199
                WHEN subscription_plan = 'enterprise' THEN 399
                ELSE 0
            END as monthly_revenue
            FROM academies
        ) as revenue_calc");
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "خطأ في قاعدة البيانات: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فهرس الأكاديميات - Vult SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .stats-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stats-card-5 { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .plan-badge { font-size: 0.8rem; padding: 0.3rem 0.6rem; }
        .academy-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .academy-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .search-box { border-radius: 25px; border: 2px solid #e9ecef; }
        .search-box:focus { border-color: #ff6b35; box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25); }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">
                        <i class="fas fa-building me-2"></i>
                        فهرس الأكاديميات المشتركة
                    </h2>
                    <div>
                        <a href="/academy-requests.php" class="btn btn-outline-primary me-2">
                            <i class="fas fa-clipboard-list me-2"></i>
                            طلبات الأكاديميات
                        </a>
                        <a href="/subscription-management.php" class="btn btn-outline-success me-2">
                            <i class="fas fa-credit-card me-2"></i>
                            إدارة الاشتراكات
                        </a>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right me-2"></i>
                            العودة للرئيسية
                        </a>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                </div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $stats['total_academies'] ?></h3>
                                <p class="mb-0">إجمالي الأكاديميات</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-2 text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $stats['active_subscriptions'] ?></h3>
                                <p class="mb-0">اشتراكات نشطة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-3 text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $stats['trial_academies'] ?></h3>
                                <p class="mb-0">تجارب مجانية</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-4 text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $stats['basic_academies'] + $stats['premium_academies'] + $stats['enterprise_academies'] ?></h3>
                                <p class="mb-0">اشتراكات مدفوعة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-5 text-dark">
                            <div class="card-body text-center">
                                <h3 class="fw-bold">$<?= number_format($stats['total_monthly_revenue']) ?></h3>
                                <p class="mb-0">الإيراد الشهري</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $stats['active_academies'] ?></h3>
                                <p class="mb-0">أكاديميات نشطة</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control search-box" id="searchInput" placeholder="البحث في الأكاديميات...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="planFilter">
                                    <option value="">جميع الباقات</option>
                                    <option value="trial">تجريبية</option>
                                    <option value="basic">أساسية</option>
                                    <option value="premium">متقدمة</option>
                                    <option value="enterprise">مؤسسية</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">جميع الحالات</option>
                                    <option value="active">نشط</option>
                                    <option value="inactive">غير نشط</option>
                                    <option value="suspended">معلق</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" onclick="filterAcademies()">
                                    <i class="fas fa-search me-2"></i>بحث
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Academies Grid -->
                <div class="row" id="academiesGrid">
                    <?php foreach ($academies as $academy): ?>
                    <div class="col-lg-4 col-md-6 mb-4 academy-item" 
                         data-name="<?= strtolower($academy['title']) ?>"
                         data-plan="<?= $academy['subscription_plan'] ?>"
                         data-status="<?= $academy['subscription_status'] ?>">
                        <div class="card academy-card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($academy['title']) ?></h6>
                                <span class="badge bg-<?= $academy['subscription_status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= $academy['subscription_status'] === 'active' ? 'نشط' : 'غير نشط' ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">معلومات الاشتراك</h6>
                                    <div class="d-flex justify-content-between">
                                        <span>الباقة:</span>
                                        <?php
                                        $planClass = '';
                                        $planText = '';
                                        switch ($academy['subscription_plan']) {
                                            case 'trial':
                                                $planClass = 'bg-warning';
                                                $planText = 'تجريبية';
                                                break;
                                            case 'basic':
                                                $planClass = 'bg-primary';
                                                $planText = 'أساسية';
                                                break;
                                            case 'premium':
                                                $planClass = 'bg-success';
                                                $planText = 'متقدمة';
                                                break;
                                            case 'enterprise':
                                                $planClass = 'bg-danger';
                                                $planText = 'مؤسسية';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $planClass ?>"><?= $planText ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>الإيراد الشهري:</span>
                                        <span class="fw-bold">$<?= number_format($academy['monthly_revenue']) ?></span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">معلومات الاتصال</h6>
                                    <p class="mb-1"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($academy['email']) ?></p>
                                    <?php if ($academy['vult_phone']): ?>
                                    <p class="mb-1"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($academy['vult_phone']) ?></p>
                                    <?php endif; ?>
                                    <?php if ($academy['vult_city']): ?>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($academy['vult_city']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($academy['vult_sports']): ?>
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">الرياضات</h6>
                                    <p class="mb-0"><?= htmlspecialchars($academy['vult_sports']) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">تواريخ مهمة</h6>
                                    <p class="mb-1"><small>تاريخ الإنشاء: <?= date('Y-m-d', strtotime($academy['created_at'])) ?></small></p>
                                    <?php if ($academy['subscription_start']): ?>
                                    <p class="mb-1"><small>بداية الاشتراك: <?= date('Y-m-d', strtotime($academy['subscription_start'])) ?></small></p>
                                    <?php endif; ?>
                                    <?php if ($academy['subscription_end']): ?>
                                    <p class="mb-1"><small>نهاية الاشتراك: <?= date('Y-m-d', strtotime($academy['subscription_end'])) ?></small></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="http://portal.localhost/academies/view/<?= $academy['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye me-1"></i>عرض
                                    </a>
                                    <a href="/subscription-management.php?academy_id=<?= $academy['id'] ?>" 
                                       class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-edit me-1"></i>تعديل
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- No Results Message -->
                <div id="noResults" class="text-center py-5" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد أكاديميات تطابق البحث</h5>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterAcademies() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const planFilter = document.getElementById('planFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const academyItems = document.querySelectorAll('.academy-item');
            let visibleCount = 0;
            
            academyItems.forEach(item => {
                const name = item.getAttribute('data-name');
                const plan = item.getAttribute('data-plan');
                const status = item.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesPlan = !planFilter || plan === planFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                if (matchesSearch && matchesPlan && matchesStatus) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            const noResults = document.getElementById('noResults');
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }
        
        // Search on input
        document.getElementById('searchInput').addEventListener('input', filterAcademies);
        document.getElementById('planFilter').addEventListener('change', filterAcademies);
        document.getElementById('statusFilter').addEventListener('change', filterAcademies);
    </script>
</body>
</html>
