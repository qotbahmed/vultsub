<?php
// Backend Portal - Academy Requests Management
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

// Get academy requests from Vult SaaS database
$requests = [];
$stats = [
    'total' => 0,
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0
];

try {
    // Connect to Vult SaaS database
    $vultDb = new PDO("mysql:host=database;dbname=vult;charset=utf8mb4", "root", "root");
    $vultDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all academy requests
    $stmt = $vultDb->prepare("SELECT * FROM academy_requests ORDER BY requested_at DESC");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stmt = $vultDb->prepare("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM academy_requests");
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage();
}

// Handle actions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    $requestId = $_POST['request_id'] ?? '';
    
    if ($action === 'approve' && $requestId) {
        try {
            // Update request status
            $stmt = $vultDb->prepare("UPDATE academy_requests SET status = 'approved', approved_at = NOW() WHERE id = ?");
            $stmt->execute([$requestId]);
            
            // Get request details
            $stmt = $vultDb->prepare("SELECT * FROM academy_requests WHERE id = ?");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($request) {
                // Create academy in Portal
                $academy = new Academies();
                $academy->title = $request['academy_name'];
                $academy->contact_email = $request['email'];
                $academy->contact_phone = $request['phone'];
                $academy->address = $request['address'];
                $academy->city_id = 1; // Default city
                $academy->district_id = 1; // Default district
                $academy->description = $request['description'];
                $academy->manager_id = 1; // Will be updated later
                $academy->main = 1;
                $academy->created_by = $user->id;
                $academy->status = 1;
                $academy->primary_color = '#1e3c72';
                $academy->secondary_color = '#ff6b35';
                $academy->accent_color = '#2a5298';
                $academy->sport_icons = $request['sports'];
                $academy->days = '1,2,3,4,5,6,7';
                $academy->startTime = '06:00:00';
                $academy->endTime = '22:00:00';
                
                if ($academy->save()) {
                    // Update user in Vult SaaS with academy_id
                    $stmt = $vultDb->prepare("UPDATE user SET academy_id = ? WHERE email = ?");
                    $stmt->execute([$academy->id, $request['email']]);
                    
                    $success = "تمت الموافقة على الأكاديمية وإنشاؤها بنجاح!";
                } else {
                    $error = "خطأ في إنشاء الأكاديمية: " . implode(', ', $academy->getFirstErrors());
                }
            }
        } catch (Exception $e) {
            $error = "خطأ في الموافقة: " . $e->getMessage();
        }
    } elseif ($action === 'reject' && $requestId) {
        try {
            $stmt = $vultDb->prepare("UPDATE academy_requests SET status = 'rejected', rejected_at = NOW() WHERE id = ?");
            $stmt->execute([$requestId]);
            $success = "تم رفض الطلب بنجاح!";
        } catch (Exception $e) {
            $error = "خطأ في الرفض: " . $e->getMessage();
        }
    }
    
    // Refresh page
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة طلبات الأكاديميات - Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .stats-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .btn-approve { background: linear-gradient(45deg, #28a745, #20c997); }
        .btn-reject { background: linear-gradient(45deg, #dc3545, #fd7e14); }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">
                        <i class="fas fa-clipboard-list me-2"></i>
                        إدارة طلبات الأكاديميات
                    </h2>
                    <a href="/" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للرئيسية
                    </a>
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
                                        <h4 class="fw-bold"><?= $stats['total'] ?></h4>
                                        <p class="mb-0">إجمالي الطلبات</p>
                                    </div>
                                    <i class="fas fa-list fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card-2 text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold"><?= $stats['pending'] ?></h4>
                                        <p class="mb-0">في الانتظار</p>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card-3 text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold"><?= $stats['approved'] ?></h4>
                                        <p class="mb-0">موافق عليها</p>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card-4 text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="fw-bold"><?= $stats['rejected'] ?></h4>
                                        <p class="mb-0">مرفوضة</p>
                                    </div>
                                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Requests Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            قائمة طلبات الأكاديميات
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الأكاديمية</th>
                                        <th>المدير</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الهاتف</th>
                                        <th>المدينة</th>
                                        <th>الرياضات</th>
                                        <th>عدد الفروع</th>
                                        <th>تاريخ الطلب</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requests as $index => $request): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($request['academy_name']) ?></td>
                                        <td><?= htmlspecialchars($request['manager_name']) ?></td>
                                        <td><?= htmlspecialchars($request['email']) ?></td>
                                        <td><?= htmlspecialchars($request['phone']) ?></td>
                                        <td><?= htmlspecialchars($request['city']) ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars($request['sports']) ?>
                                            </span>
                                        </td>
                                        <td><?= $request['branches_count'] ?></td>
                                        <td><?= date('Y-m-d H:i', strtotime($request['requested_at'])) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($request['status']) {
                                                case 'pending':
                                                    $statusClass = 'bg-warning';
                                                    $statusText = 'في الانتظار';
                                                    break;
                                                case 'approved':
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'موافق عليها';
                                                    break;
                                                case 'rejected':
                                                    $statusClass = 'bg-danger';
                                                    $statusText = 'مرفوضة';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td>
                                            <?php if ($request['status'] === 'pending'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-approve text-white me-1" 
                                                        onclick="return confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-reject text-white" 
                                                        onclick="return confirm('هل أنت متأكد من رفض هذا الطلب؟')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            <?php else: ?>
                                            <span class="text-muted">-</span>
                                            <?php endif; ?>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
