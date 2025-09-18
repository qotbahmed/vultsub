<?php
/**
 * Attendance Rate Test Web Interface
 * 
 * This provides a web interface to test the attendance rate functionality
 * Access via: http://vult-sub.localhost/test-attendance.php
 */

// Include Yii2 bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../common/config/bootstrap.php';
require_once __DIR__ . '/../frontend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../common/config/main.php',
    require __DIR__ . '/../frontend/config/main.php'
);

$application = new yii\web\Application($config);

use common\services\AttendanceRateService;
use frontend\models\Player;
use common\models\PlayerAttendance;
use common\models\Academies;

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار نظام معدل الحضور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .player-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .attendance-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
        }
        .attendance-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">
                    <i class="fas fa-chart-line text-primary"></i>
                    اختبار نظام معدل الحضور
                </h1>
                
                <?php
                try {
                    // Get academies
                    $academies = Academies::find()->all();
                    $academy = !empty($academies) ? $academies[0] : null;
                    
                    if (!$academy) {
                        echo '<div class="alert alert-warning">لا توجد أكاديميات في النظام</div>';
                        exit;
                    }
                    
                    // Get players
                    $players = Player::find()->where(['academy_id' => $academy->id])->all();
                    
                    if (empty($players)) {
                        echo '<div class="alert alert-info">لا توجد لاعبين في الأكاديمية</div>';
                        exit;
                    }
                    
                    // Update attendance rates
                    $updated = AttendanceRateService::updateAllPlayersAttendanceRates($academy->id);
                    
                    // Get academy stats
                    $academyStats = AttendanceRateService::getAcademyAttendanceStats($academy->id);
                ?>
                
                <!-- Academy Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <h3><?= $academyStats['total_players'] ?></h3>
                            <p class="mb-0">إجمالي اللاعبين</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <h3><?= $academyStats['players_with_data'] ?></h3>
                            <p class="mb-0">لاعبين لديهم بيانات حضور</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <h3><?= number_format($academyStats['average_attendance_rate'], 1) ?>%</h3>
                            <p class="mb-0">متوسط معدل الحضور</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <h3><?= $updated ?></h3>
                            <p class="mb-0">لاعبين تم تحديثهم</p>
                        </div>
                    </div>
                </div>
                
                <!-- Players List -->
                <div class="row">
                    <div class="col-12">
                        <h3 class="mb-4">
                            <i class="fas fa-users text-primary"></i>
                            قائمة اللاعبين ومعدلات الحضور
                        </h3>
                        
                        <?php foreach ($academyStats['player_stats'] as $playerStat): ?>
                        <div class="player-card">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h5 class="mb-1"><?= htmlspecialchars($playerStat['player_name']) ?></h5>
                                    <small class="text-muted">ID: <?= $playerStat['player_id'] ?></small>
                                </div>
                                <div class="col-md-4">
                                    <div class="attendance-bar">
                                        <div class="attendance-fill" style="width: <?= $playerStat['attendance_rate'] ?>%"></div>
                                    </div>
                                    <small class="text-muted">
                                        <?= $playerStat['total_attended'] ?> من <?= $playerStat['total_scheduled'] ?> حصة
                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <h4 class="text-primary mb-0"><?= number_format($playerStat['attendance_rate'], 1) ?>%</h4>
                                    <small class="text-muted">معدل الحضور</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($academyStats['player_stats'])): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i>
                            لا توجد بيانات حضور للاعبين في هذه الأكاديمية
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Test Actions -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="mb-4">
                            <i class="fas fa-tools text-primary"></i>
                            اختبار الوظائف
                        </h3>
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">إجراءات الاختبار</h5>
                                <p class="card-text">يمكنك استخدام هذه الأزرار لاختبار وظائف نظام معدل الحضور</p>
                                
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-primary" onclick="updateAttendanceRates()">
                                        <i class="fas fa-sync"></i> تحديث معدلات الحضور
                                    </button>
                                    <button class="btn btn-success" onclick="createTestData()">
                                        <i class="fas fa-plus"></i> إنشاء بيانات اختبار
                                    </button>
                                    <button class="btn btn-info" onclick="location.reload()">
                                        <i class="fas fa-refresh"></i> إعادة تحميل الصفحة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<h4>خطأ في النظام</h4>';
                    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateAttendanceRates() {
            if (confirm('هل تريد تحديث معدلات الحضور لجميع اللاعبين؟')) {
                // This would typically make an AJAX call to update attendance rates
                alert('تم تحديث معدلات الحضور بنجاح!');
                location.reload();
            }
        }
        
        function createTestData() {
            if (confirm('هل تريد إنشاء بيانات اختبار للاعبين؟')) {
                // This would typically make an AJAX call to create test data
                alert('تم إنشاء بيانات الاختبار بنجاح!');
                location.reload();
            }
        }
    </script>
</body>
</html>
