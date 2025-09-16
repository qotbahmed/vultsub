<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'لوحة تحكم التجربة - Vult';
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
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
        }
        
        .trial-banner {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .trial-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .feature-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .btn-trial {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-trial:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
            color: white;
        }
        
        .countdown {
            font-size: 2rem;
            font-weight: bold;
            color: #ff6b35;
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
                        <i class="fas fa-rocket me-3"></i>لوحة تحكم التجربة المجانية
                    </h1>
                    <p class="lead mb-0">استكشف جميع مميزات Vult خلال تجربتك المجانية</p>
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
        <!-- Trial Banner -->
        <div class="trial-banner">
            <h5 class="mb-2"><i class="fas fa-clock me-2"></i>حساب تجريبي</h5>
            <p class="mb-0">
                <?php if ($trialData['is_trial_active']): ?>
                    لديك <?= $trialData['trial_days_left'] ?> أيام متبقية في التجربة المجانية. 
                    <a href="<?= Url::to(['home/pricing']) ?>" class="text-white text-decoration-underline fw-bold">ترقية الآن</a> للاستمرار في استخدام جميع المميزات.
                <?php else: ?>
                    انتهت فترة التجربة المجانية. 
                    <a href="<?= Url::to(['home/pricing']) ?>" class="text-white text-decoration-underline fw-bold">ترقية الآن</a> للاستمرار في استخدام جميع المميزات.
                <?php endif; ?>
            </p>
        </div>

        <!-- Trial Status Card -->
        <div class="trial-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-3">حالة تجربتك المجانية</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>اسم الأكاديمية:</strong> <?= Html::encode($trialData['academy_name']) ?></p>
                            <p class="mb-2"><strong>البريد الإلكتروني:</strong> <?= Html::encode($trialData['user_email']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>حالة الطلب:</strong> 
                                <span class="badge bg-<?= $trialData['request_status'] === 'approved' ? 'success' : ($trialData['request_status'] === 'pending' ? 'warning' : 'danger') ?>">
                                    <?= $trialData['request_status'] === 'approved' ? 'موافق عليها' : ($trialData['request_status'] === 'pending' ? 'في الانتظار' : 'مرفوضة') ?>
                                </span>
                            </p>
                            <p class="mb-2"><strong>الأيام المتبقية:</strong> 
                                <span class="countdown"><?= $trialData['trial_days_left'] ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?= Url::to(['home/pricing']) ?>" class="btn btn-trial btn-lg">
                        <i class="fas fa-crown me-2"></i>ترقية الآن
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">إدارة اللاعبين</h5>
                    <p class="text-muted">إضافة وإدارة بيانات اللاعبين</p>
                    <a href="<?= Url::to(['dashboard/players-management']) ?>" class="btn btn-outline-primary">ابدأ الآن</a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">إدارة الجدولة</h5>
                    <p class="text-muted">إنشاء وإدارة جداول التدريب</p>
                    <button class="btn btn-outline-success" onclick="showComingSoon()">قريباً</button>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">التقارير</h5>
                    <p class="text-muted">تقارير شاملة عن الأداء</p>
                    <button class="btn btn-outline-info" onclick="showComingSoon()">قريباً</button>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <i class="fas fa-credit-card fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold">إدارة المدفوعات</h5>
                    <p class="text-muted">تتبع الاشتراكات والمدفوعات</p>
                    <button class="btn btn-outline-warning" onclick="showComingSoon()">قريباً</button>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <i class="fas fa-mobile-alt fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">تطبيق موبايل</h5>
                    <p class="text-muted">تطبيق للاعبين وأولياء الأمور</p>
                    <button class="btn btn-outline-danger" onclick="showComingSoon()">قريباً</button>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <i class="fas fa-cog fa-3x text-secondary mb-3"></i>
                    <h5 class="fw-bold">الإعدادات</h5>
                    <p class="text-muted">تخصيص إعدادات الأكاديمية</p>
                    <button class="btn btn-outline-secondary" onclick="showComingSoon()">قريباً</button>
                </div>
            </div>
        </div>

        <!-- Upgrade Section -->
        <?php if ($trialData['trial_days_left'] <= 3 || !$trialData['is_trial_active']): ?>
        <div class="trial-card">
            <div class="text-center">
                <h4 class="fw-bold mb-3">ترقية إلى النسخة الكاملة</h4>
                <p class="text-muted mb-4">احصل على جميع المميزات المتقدمة مع خصم خاص للمشتركين الجدد</p>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <h5 class="fw-bold">البداية</h5>
                            <div class="h3 text-primary">99 ريال/شهر</div>
                            <ul class="list-unstyled">
                                <li>حتى فرعين</li>
                                <li>حتى 100 لاعب</li>
                                <li>دعم بالبريد الإلكتروني</li>
                            </ul>
                            <button class="btn btn-outline-primary" onclick="selectPlan('basic')">اختيار</button>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card" style="border: 2px solid #ff6b35;">
                            <h5 class="fw-bold">المهنية</h5>
                            <div class="h3 text-primary">299 ريال/شهر</div>
                            <div class="badge bg-warning mb-2">الأكثر شعبية</div>
                            <ul class="list-unstyled">
                                <li>حتى 5 فروع</li>
                                <li>حتى 500 لاعب</li>
                                <li>دعم أولوية</li>
                                <li>تطبيق موبايل</li>
                            </ul>
                            <button class="btn btn-trial" onclick="selectPlan('professional')">اختيار</button>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card">
                            <h5 class="fw-bold">المؤسسية</h5>
                            <div class="h3 text-primary">599 ريال/شهر</div>
                            <ul class="list-unstyled">
                                <li>فروع غير محدودة</li>
                                <li>لاعبين غير محدود</li>
                                <li>دعم 24/7</li>
                                <li>واجهة برمجة</li>
                            </ul>
                            <button class="btn btn-outline-primary" onclick="selectPlan('enterprise')">اختيار</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPlan(plan) {
            alert(`تم اختيار الخطة: ${plan}\nسيتم توجيهك إلى صفحة الدفع...`);
            // في التطبيق الحقيقي، هنا ستوجه إلى صفحة الدفع
        }
        
        function showComingSoon() {
            alert('هذه الميزة ستكون متاحة قريباً في النسخة الكاملة!');
        }
    </script>
</body>
</html>