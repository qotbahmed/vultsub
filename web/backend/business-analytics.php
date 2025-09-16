<?php
// Business Analytics Dashboard
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

// Get comprehensive analytics
$analytics = [];

try {
    // Vult SaaS Analytics
    $stmt = $vultDb->prepare("SELECT 
        COUNT(*) as total_requests,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_requests,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests,
        SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired_requests
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
    
    // Monthly Growth
    $stmt = $portalDb->prepare("SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as new_academies
        FROM academies 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC");
    $stmt->execute();
    $analytics['monthly_growth'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Geographic Distribution
    $stmt = $portalDb->prepare("SELECT 
        city,
        COUNT(*) as count
        FROM academies 
        WHERE city IS NOT NULL AND city != ''
        GROUP BY city
        ORDER BY count DESC
        LIMIT 10");
    $stmt->execute();
    $analytics['geographic_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Sports Distribution
    $stmt = $portalDb->prepare("SELECT 
        sport_icons,
        COUNT(*) as count
        FROM academies 
        WHERE sport_icons IS NOT NULL AND sport_icons != ''
        GROUP BY sport_icons
        ORDER BY count DESC
        LIMIT 10");
    $stmt->execute();
    $analytics['sports_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "خطأ في قاعدة البيانات: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحليلات الأعمال - Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .stats-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stats-card-5 { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .chart-container { position: relative; height: 300px; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">
                        <i class="fas fa-chart-line me-2"></i>
                        تحليلات الأعمال الشاملة
                    </h2>
                    <div>
                        <a href="/subscription-management.php" class="btn btn-outline-primary me-2">
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
                
                <!-- Key Metrics -->
                <div class="row mb-4">
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $analytics['vult_requests']['total_requests'] ?></h3>
                                <p class="mb-0">إجمالي الطلبات</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-2 text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $analytics['portal_academies']['total_academies'] ?></h3>
                                <p class="mb-0">الأكاديميات</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-3 text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $analytics['portal_academies']['active_academies'] ?></h3>
                                <p class="mb-0">نشطة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-4 text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold">$<?= number_format($analytics['total_monthly_revenue']) ?></h3>
                                <p class="mb-0">الإيراد الشهري</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card-5 text-dark">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $analytics['conversion_rate'] ?>%</h3>
                                <p class="mb-0">معدل التحويل</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card stats-card text-white">
                            <div class="card-body text-center">
                                <h3 class="fw-bold"><?= $analytics['trial_stats']['active_trials'] ?></h3>
                                <p class="mb-0">تجارب نشطة</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">توزيع الباقات</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="planDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">الإيرادات الشهرية</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Analytics -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">التوزيع الجغرافي</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>المدينة</th>
                                                <th>عدد الأكاديميات</th>
                                                <th>النسبة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($analytics['geographic_distribution'] as $geo): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($geo['city']) ?></td>
                                                <td><?= $geo['count'] ?></td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar" style="width: <?= ($geo['count'] / $analytics['portal_academies']['total_academies']) * 100 ?>%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">توزيع الرياضات</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>الرياضة</th>
                                                <th>عدد الأكاديميات</th>
                                                <th>النسبة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($analytics['sports_distribution'] as $sport): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($sport['sport_icons']) ?></td>
                                                <td><?= $sport['count'] ?></td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" style="width: <?= ($sport['count'] / $analytics['portal_academies']['total_academies']) * 100 ?>%"></div>
                                                    </div>
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
                
                <!-- Monthly Growth -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">النمو الشهري</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="growthChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Plan Distribution Chart
        const planCtx = document.getElementById('planDistributionChart').getContext('2d');
        new Chart(planCtx, {
            type: 'doughnut',
            data: {
                labels: ['تجريبية', 'أساسية', 'متقدمة', 'مؤسسية'],
                datasets: [{
                    data: [
                        <?= $analytics['portal_academies']['trial_academies'] ?>,
                        <?= $analytics['portal_academies']['basic_academies'] ?>,
                        <?= $analytics['portal_academies']['premium_academies'] ?>,
                        <?= $analytics['portal_academies']['enterprise_academies'] ?>
                    ],
                    backgroundColor: ['#ffc107', '#007bff', '#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['أساسية', 'متقدمة', 'مؤسسية'],
                datasets: [{
                    label: 'الإيراد الشهري ($)',
                    data: [
                        <?= $analytics['revenue_breakdown'][1]['count'] * $analytics['revenue_breakdown'][1]['monthly_revenue'] ?>,
                        <?= $analytics['revenue_breakdown'][2]['count'] * $analytics['revenue_breakdown'][2]['monthly_revenue'] ?>,
                        <?= $analytics['revenue_breakdown'][3]['count'] * $analytics['revenue_breakdown'][3]['monthly_revenue'] ?>
                    ],
                    backgroundColor: ['#007bff', '#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: [<?php foreach (array_reverse($analytics['monthly_growth']) as $month): ?>'<?= $month['month'] ?>',<?php endforeach; ?>],
                datasets: [{
                    label: 'أكاديميات جديدة',
                    data: [<?php foreach (array_reverse($analytics['monthly_growth']) as $month): ?><?= $month['new_academies'] ?>,<?php endforeach; ?>],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
