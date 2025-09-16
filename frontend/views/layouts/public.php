<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

$assetDir = Yii::$app->assetManager->getPublishedUrl('@frontend/web');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="rtl">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #e55a2b, #e8841a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid #ff6b35;
            color: #ff6b35;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: #ff6b35;
            border-color: #ff6b35;
            color: white;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: #ff6b35 !important;
        }
        
        .navbar-nav .nav-link.active {
            color: #ff6b35 !important;
            font-weight: 600;
        }
        
        .footer {
            background: #1e3c72;
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .footer a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: #ff6b35;
        }
        
        .footer .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .footer .social-links a:hover {
            background: #ff6b35;
            transform: translateY(-3px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .content-section {
            min-height: calc(100vh - 200px);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: #1e3c72 !important;
        }
        
        .navbar-nav .nav-link {
            color: #333 !important;
        }
    </style>
    
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= Url::to(['home/index']) ?>">
            <i class="fas fa-futbol me-2"></i>Vult
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= Yii::$app->controller->id == 'home' && Yii::$app->controller->action->id == 'index' ? 'active' : '' ?>" 
                       href="<?= Url::to(['home/index']) ?>">الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= Yii::$app->controller->id == 'home' && Yii::$app->controller->action->id == 'pricing' ? 'active' : '' ?>" 
                       href="<?= Url::to(['home/pricing']) ?>">الأسعار</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['home/index']) ?>#features">المميزات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['home/index']) ?>#contact">اتصل بنا</a>
                </li>
            </ul>
            <div class="d-flex">
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['auth/login']) ?>" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                    </a>
                    <a href="<?= Url::to(['auth/register']) ?>" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>ابدأ مجاناً
                    </a>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i><?= Yii::$app->user->identity->username ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= Url::to(['dashboard/trial-dashboard']) ?>">
                                <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                            </a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['auth/logout']) ?>" data-method="post">
                                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                            </a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="content-section" style="padding-top: 80px;">
    <?= $content ?>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-futbol me-2"></i>Vult
                </h5>
                <p class="text-muted">منصة شاملة لإدارة الأكاديميات الرياضية، مصممة لمساعدتك على النمو والنجاح.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">الروابط</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?= Url::to(['home/index']) ?>">الرئيسية</a></li>
                    <li class="mb-2"><a href="<?= Url::to(['home/pricing']) ?>">الأسعار</a></li>
                    <li class="mb-2"><a href="<?= Url::to(['home/index']) ?>#features">المميزات</a></li>
                    <li class="mb-2"><a href="<?= Url::to(['auth/login']) ?>">تسجيل الدخول</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">الدعم</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">مركز المساعدة</a></li>
                    <li class="mb-2"><a href="#">اتصل بنا</a></li>
                    <li class="mb-2"><a href="#">الأسئلة الشائعة</a></li>
                    <li class="mb-2"><a href="#">الدعم الفني</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">تواصل معنا</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:info@vult.com">info@vult.com</a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:+966501234567">+966 50 123 4567</a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        الرياض، المملكة العربية السعودية
                    </li>
                </ul>
            </div>
        </div>
        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">&copy; 2024 Vult. جميع الحقوق محفوظة.</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="#" class="text-muted me-3">سياسة الخصوصية</a>
                <a href="#" class="text-muted">شروط الاستخدام</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
