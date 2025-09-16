<?php
// Subscription Management System
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

// Handle actions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update-subscription') {
        $academyId = $_POST['academy_id'] ?? '';
        $plan = $_POST['plan'] ?? '';
        $status = $_POST['status'] ?? '';
        
        try {
            // Update academy subscription
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
            
            $success = "تم تحديث الاشتراك بنجاح!";
            
        } catch (Exception $e) {
            $error = "خطأ في تحديث الاشتراك: " . $e->getMessage();
        }
    }
}

// Get academies with subscription info
$academies = [];
$stats = [];

try {
    $stmt = $portalDb->prepare("SELECT 
        a.*, u.email, u.status as user_status,
        CASE 
            WHEN a.subscription_plan = 'trial' THEN 0
            WHEN a.subscription_plan = 'basic' THEN 99
            WHEN a.subscription_plan = 'premium' THEN 199
            WHEN a.subscription_plan = 'enterprise' THEN 399
            ELSE 0
        END as monthly_revenue
        FROM academies a
        LEFT JOIN user u ON a.id = u.academy_id
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
    <title>إدارة الاشتراكات - Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .stats-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .plan-badge { font-size: 0.8rem; padding: 0.3rem 0.6rem; }
        .revenue-card { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">
                        <i class="fas fa-credit-card me-2"></i>
                        إدارة الاشتراكات والباقات
                    </h2>
                    <div>
                        <a href="/academy-requests.php" class="btn btn-outline-primary me-2">
                            <i class="fas fa-clipboard-list me-2"></i>
                            طلبات الأكاديميات
                        </a>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right me-2"></i>
                            العودة للرئيسية
                        </a>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold"><?= $stats['total_academies'] ?></h4>
                                        <p class="mb-0">إجمالي الأكاديميات</p>
                                    </div>
                                    <i class="fas fa-building fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card-2 text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold"><?= $stats['active_subscriptions'] ?></h4>
                                        <p class="mb-0">اشتراكات نشطة</p>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card-3 text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold"><?= $stats['trial_academies'] ?></h4>
                                        <p class="mb-0">تجارب مجانية</p>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card revenue-card text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold">$<?= number_format($stats['total_monthly_revenue']) ?></h4>
                                        <p class="mb-0">الإيرادات الشهرية</p>
                                    </div>
                                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Plan Distribution -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">توزيع الباقات</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted">تجريبية</h6>
                                            <h4 class="text-warning"><?= $stats['trial_academies'] ?></h4>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted">أساسية</h6>
                                            <h4 class="text-primary"><?= $stats['basic_academies'] ?></h4>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted">متقدمة</h6>
                                            <h4 class="text-success"><?= $stats['premium_academies'] ?></h4>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted">مؤسسية</h6>
                                            <h4 class="text-danger"><?= $stats['enterprise_academies'] ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">الإيرادات حسب الباقة</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-1">أساسية (99$)</p>
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar bg-primary" style="width: <?= $stats['basic_academies'] > 0 ? ($stats['basic_academies'] / $stats['total_academies']) * 100 : 0 ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1">متقدمة (199$)</p>
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: <?= $stats['premium_academies'] > 0 ? ($stats['premium_academies'] / $stats['total_academies']) * 100 : 0 ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1">مؤسسية (399$)</p>
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar bg-danger" style="width: <?= $stats['enterprise_academies'] > 0 ? ($stats['enterprise_academies'] / $stats['total_academies']) * 100 : 0 ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1">تجريبية (0$)</p>
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: <?= $stats['trial_academies'] > 0 ? ($stats['trial_academies'] / $stats['total_academies']) * 100 : 0 ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Academies Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            قائمة الأكاديميات والاشتراكات
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الأكاديمية</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الباقة</th>
                                        <th>الحالة</th>
                                        <th>الإيراد الشهري</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($academies as $index => $academy): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($academy['title']) ?></td>
                                        <td><?= htmlspecialchars($academy['email']) ?></td>
                                        <td>
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
                                            <span class="badge <?= $planClass ?> plan-badge"><?= $planText ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $academy['subscription_status'] === 'active' ? 'success' : 'danger' ?>">
                                                <?= $academy['subscription_status'] === 'active' ? 'نشط' : 'غير نشط' ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold">$<?= number_format($academy['monthly_revenue']) ?></td>
                                        <td><?= date('Y-m-d', strtotime($academy['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                    data-bs-target="#editModal" 
                                                    data-academy-id="<?= $academy['id'] ?>"
                                                    data-current-plan="<?= $academy['subscription_plan'] ?>"
                                                    data-current-status="<?= $academy['subscription_status'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الاشتراك</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update-subscription">
                        <input type="hidden" name="academy_id" id="modal-academy-id">
                        
                        <div class="mb-3">
                            <label for="plan" class="form-label">الباقة</label>
                            <select class="form-select" name="plan" id="modal-plan" required>
                                <option value="trial">تجريبية (0$)</option>
                                <option value="basic">أساسية (99$)</option>
                                <option value="premium">متقدمة (199$)</option>
                                <option value="enterprise">مؤسسية (399$)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select" name="status" id="modal-status" required>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                                <option value="suspended">معلق</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var academyId = button.getAttribute('data-academy-id');
            var currentPlan = button.getAttribute('data-current-plan');
            var currentStatus = button.getAttribute('data-current-status');
            
            document.getElementById('modal-academy-id').value = academyId;
            document.getElementById('modal-plan').value = currentPlan;
            document.getElementById('modal-status').value = currentStatus;
        });
    </script>
</body>
</html>
