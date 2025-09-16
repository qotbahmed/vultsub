<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'لوحة الإدارة - Vult';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .stats-card.danger {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .admin-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            color: inherit;
        }
        
        .recent-activity {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 font-weight-bold mb-3">
                        <i class="fas fa-tachometer-alt me-3"></i>لوحة الإدارة
                    </h1>
                    <p class="lead mb-0">نظرة شاملة على النظام والإحصائيات</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?= Url::to(['home/index']) ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>العودة للرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card success">
                    <h3 class="mb-1"><?= $stats['total_requests'] ?></h3>
                    <small>إجمالي الطلبات</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <h3 class="mb-1"><?= $stats['total_users'] ?></h3>
                    <small>إجمالي المستخدمين</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <h3 class="mb-1"><?= $stats['pending_requests'] ?></h3>
                    <small>طلبات في الانتظار</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card danger">
                    <h3 class="mb-1"><?= $stats['rejected_requests'] ?></h3>
                    <small>طلبات مرفوضة</small>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">إحصائيات الطلبات الشهرية</h5>
                    <canvas id="requestsChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">توزيع الحالات</h5>
                    <canvas id="statusChart" width="300" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Admin Tools Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="recent-activity">
                    <h5 class="fw-bold mb-4">النشاط الأخير</h5>
                    <?= GridView::widget([
                        'dataProvider' => new yii\data\ArrayDataProvider([
                            'allModels' => $recentRequests,
                            'pagination' => ['pageSize' => 5]
                        ]),
                        'columns' => [
                            [
                                'attribute' => 'academy_name',
                                'label' => 'اسم الأكاديمية',
                            ],
                            [
                                'attribute' => 'manager_name',
                                'label' => 'المدير',
                            ],
                            [
                                'attribute' => 'email',
                                'label' => 'البريد الإلكتروني',
                            ],
                            [
                                'attribute' => 'status',
                                'label' => 'الحالة',
                                'value' => function ($model) {
                                    $statusMap = [
                                        'pending' => 'في الانتظار',
                                        'approved' => 'موافق عليها',
                                        'rejected' => 'مرفوضة'
                                    ];
                                    return $statusMap[$model->status] ?? $model->status;
                                },
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $statusMap = [
                                        'pending' => ['text' => 'في الانتظار', 'class' => 'warning'],
                                        'approved' => ['text' => 'موافق عليها', 'class' => 'success'],
                                        'rejected' => ['text' => 'مرفوضة', 'class' => 'danger']
                                    ];
                                    $status = $statusMap[$model->status] ?? ['text' => $model->status, 'class' => 'secondary'];
                                    return '<span class="badge bg-' . $status['class'] . '">' . $status['text'] . '</span>';
                                }
                            ],
                            [
                                'attribute' => 'requested_at',
                                'label' => 'تاريخ الطلب',
                                'value' => function ($model) {
                                    return Yii::$app->formatter->asDatetime($model->requested_at, 'dd/MM/yyyy hh:mm a');
                                }
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-card">
                    <h5 class="fw-bold mb-4">أدوات الإدارة</h5>
                    <div class="d-grid gap-2">
                        <a href="<?= Url::to(['academy-request/index']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-clipboard-list me-2"></i>إدارة طلبات الأكاديميات
                        </a>
                        <a href="<?= Url::to(['dashboard/players-management']) ?>" class="btn btn-outline-success">
                            <i class="fas fa-users me-2"></i>إدارة اللاعبين
                        </a>
                        <a href="<?= Url::to(['home/academy-simple']) ?>" class="btn btn-outline-info">
                            <i class="fas fa-tachometer-alt me-2"></i>لوحة تحكم الأكاديمية
                        </a>
                        <a href="<?= Url::to(['home/pricing']) ?>" class="btn btn-outline-warning">
                            <i class="fas fa-tag me-2"></i>إدارة الأسعار
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">أداء النظام</h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-3">
                                <h4 class="text-success">99.9%</h4>
                                <small class="text-muted">وقت التشغيل</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <h4 class="text-info">120ms</h4>
                                <small class="text-muted">زمن الاستجابة</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <h4 class="text-warning"><?= $stats['active_sessions'] ?></h4>
                                <small class="text-muted">الجلسات النشطة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="fw-bold mb-4">إحصائيات سريعة</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary"><?= $stats['approved_requests'] ?></h4>
                                <small class="text-muted">طلبات موافق عليها</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success"><?= round(($stats['approved_requests'] / max($stats['total_requests'], 1)) * 100, 1) ?>%</h4>
                                <small class="text-muted">معدل الموافقة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Requests Chart
        const requestsCtx = document.getElementById('requestsChart').getContext('2d');
        new Chart(requestsCtx, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر'],
                datasets: [{
                    label: 'طلبات جديدة',
                    data: [12, 19, 15, 25, 22, 18, 30, 28, 24],
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4
                }, {
                    label: 'طلبات موافق عليها',
                    data: [8, 15, 12, 20, 18, 14, 25, 22, 19],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['موافق عليها', 'في الانتظار', 'مرفوضة'],
                datasets: [{
                    data: [<?= $stats['approved_requests'] ?>, <?= $stats['pending_requests'] ?>, <?= $stats['rejected_requests'] ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
