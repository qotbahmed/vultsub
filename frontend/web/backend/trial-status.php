<?php
// Backend Portal - Trial Status Management
require_once '../common/config/main.php';

use yii\web\Application;
use common\models\User;
use common\models\Academies;

$config = require '../common/config/main.php';
$app = new Application($config);

// Check if user is logged in
if (Yii::$app->user->isGuest) {
    header('Location: /sign-in/login');
    exit;
}

$user = Yii::$app->user->identity;

// Get trial status from Vult SaaS database
$trialStatus = null;
$academy = null;

try {
    // Connect to Vult SaaS database
    $vultDb = new PDO("mysql:host=database;dbname=vult;charset=utf8mb4", "root", "root");
    $vultDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user trial status
    $stmt = $vultDb->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$user->email]);
    $trialUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($trialUser) {
        $trialStatus = [
            'is_trial_active' => $trialUser['trial_expires_at'] && $trialUser['trial_expires_at'] > time(),
            'trial_days_left' => $trialUser['trial_expires_at'] ? max(0, ceil(($trialUser['trial_expires_at'] - time()) / (24 * 60 * 60))) : 0,
            'trial_start' => $trialUser['trial_started_at'],
            'trial_end' => $trialUser['trial_expires_at'],
            'academy_id' => $trialUser['academy_id'],
            'status' => $trialUser['status']
        ];
        
        // Get academy info if linked
        if ($trialUser['academy_id']) {
            $academy = Academies::findOne($trialUser['academy_id']);
        }
    }
    
} catch (PDOException $e) {
    $error = "خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حالة التجربة - Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .trial-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .trial-card-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .trial-card-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .progress { height: 25px; border-radius: 15px; }
        .progress-bar { border-radius: 15px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">
                        <i class="fas fa-clock me-2"></i>
                        حالة التجربة المجانية
                    </h2>
                    <a href="/" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للرئيسية
                    </a>
                </div>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                </div>
                <?php endif; ?>
                
                <?php if ($trialStatus): ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card trial-card text-white mb-4">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    تفاصيل التجربة
                                </h4>
                                
                                <?php if ($trialStatus['is_trial_active']): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>الحالة:</strong> نشطة</p>
                                        <p class="mb-2"><strong>الأيام المتبقية:</strong> <?= $trialStatus['trial_days_left'] ?> يوم</p>
                                        <p class="mb-2"><strong>تاريخ البداية:</strong> <?= date('Y-m-d H:i', $trialStatus['trial_start']) ?></p>
                                        <p class="mb-0"><strong>تاريخ الانتهاء:</strong> <?= date('Y-m-d H:i', $trialStatus['trial_end']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="progress mb-3">
                                            <?php 
                                            $progress = (7 - $trialStatus['trial_days_left']) / 7 * 100;
                                            ?>
                                            <div class="progress-bar" style="width: <?= $progress ?>%"></div>
                                        </div>
                                        <p class="text-center mb-0">تقدم التجربة</p>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                    <h5>انتهت التجربة المجانية</h5>
                                    <p>يرجى الترقية للاستمرار في استخدام المنصة</p>
                                    <a href="http://vult-saas.localhost/?subdomain=pricing" class="btn btn-light">
                                        <i class="fas fa-arrow-up me-2"></i>
                                        الترقية الآن
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($academy): ?>
                        <div class="card trial-card-2 text-white">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">
                                    <i class="fas fa-building me-2"></i>
                                    معلومات الأكاديمية
                                </h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>اسم الأكاديمية:</strong> <?= $academy->title ?></p>
                                        <p class="mb-2"><strong>البريد الإلكتروني:</strong> <?= $academy->contact_email ?></p>
                                        <p class="mb-2"><strong>الهاتف:</strong> <?= $academy->contact_phone ?></p>
                                        <p class="mb-0"><strong>العنوان:</strong> <?= $academy->address ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>الحالة:</strong> 
                                            <span class="badge bg-<?= $academy->status ? 'success' : 'danger' ?>">
                                                <?= $academy->status ? 'نشطة' : 'غير نشطة' ?>
                                            </span>
                                        </p>
                                        <p class="mb-2"><strong>تاريخ الإنشاء:</strong> <?= date('Y-m-d', strtotime($academy->created_at)) ?></p>
                                        <p class="mb-0"><strong>الرياضات:</strong> <?= $academy->sport_icons ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card trial-card-3 text-white">
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-3">إجراءات سريعة</h5>
                                
                                <?php if ($trialStatus['is_trial_active']): ?>
                                <a href="/academies" class="btn btn-light btn-lg w-100 mb-3">
                                    <i class="fas fa-cogs me-2"></i>
                                    إدارة الأكاديمية
                                </a>
                                <a href="/players" class="btn btn-light btn-lg w-100 mb-3">
                                    <i class="fas fa-users me-2"></i>
                                    إدارة اللاعبين
                                </a>
                                <a href="/schedules" class="btn btn-light btn-lg w-100 mb-3">
                                    <i class="fas fa-calendar me-2"></i>
                                    الجدولة
                                </a>
                                <?php endif; ?>
                                
                                <a href="http://vult-saas.localhost/?subdomain=pricing" class="btn btn-warning btn-lg w-100">
                                    <i class="fas fa-crown me-2"></i>
                                    الترقية
                                </a>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">مميزات التجربة</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> إدارة اللاعبين</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> جدولة الحصص</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> تتبع الحضور</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> تقارير أساسية</li>
                                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i> دعم فني</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <h5>لا توجد تجربة مجانية</h5>
                        <p class="text-muted">لم يتم العثور على تجربة مجانية مرتبطة بحسابك.</p>
                        <a href="http://vult-saas.localhost/?subdomain=signup" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            بدء تجربة مجانية
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
