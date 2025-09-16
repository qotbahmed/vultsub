<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vult SaaS - منصة إدارة الأكاديميات الرياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .hero-section { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%); min-height: 100vh; }
        .feature-card { transition: transform 0.3s ease; }
        .feature-card:hover { transform: translateY(-10px); }
        .btn-primary { background: linear-gradient(45deg, #ff6b35, #f7931e); border: none; }
        .btn-primary:hover { background: linear-gradient(45deg, #e55a2b, #e8841a); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-futbol me-2"></i>Vult
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">المميزات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">الأسعار</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">اتصل بنا</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="/sign-in/login.php" class="btn btn-outline-light me-2">تسجيل الدخول</a>
                    <a href="/?subdomain=signup" class="btn btn-primary">ابدأ مجاناً</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        منصة شاملة لإدارة الأكاديميات الرياضية
                    </h1>
                    <p class="lead text-white mb-4">
                        احترف إدارة أكاديميتك الرياضية مع Vult - منصة متكاملة تتيح لك إدارة اللاعبين، 
                        الجدولة، المدفوعات، والتقارير بسهولة تامة.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="/?subdomain=signup" class="btn btn-primary btn-lg">
                            <i class="fas fa-rocket me-2"></i>ابدأ تجربتك المجانية
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-play me-2"></i>شاهد المميزات
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="fas fa-futbol fa-10x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">مميزات المنصة</h2>
                <p class="lead text-muted">كل ما تحتاجه لإدارة أكاديميتك الرياضية بكفاءة</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100 border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-users fa-3x text-primary"></i>
                            </div>
                            <h5 class="fw-bold">إدارة اللاعبين</h5>
                            <p class="text-muted">سجل وإدارة بيانات اللاعبين، متابعة التقدم، وإدارة الاشتراكات</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100 border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-calendar-alt fa-3x text-success"></i>
                            </div>
                            <h5 class="fw-bold">جدولة الحصص</h5>
                            <p class="text-muted">نظم جدول الحصص، المدربين، والملاعب بسهولة ومرونة</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100 border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-credit-card fa-3x text-warning"></i>
                            </div>
                            <h5 class="fw-bold">إدارة المدفوعات</h5>
                            <p class="text-muted">تتبع المدفوعات، الفواتير، والاشتراكات تلقائياً</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100 border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-chart-bar fa-3x text-info"></i>
                            </div>
                            <h5 class="fw-bold">التقارير والإحصائيات</h5>
                            <p class="text-muted">تقارير شاملة عن الأداء، الإيرادات، واللاعبين</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100 border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-mobile-alt fa-3x text-danger"></i>
                            </div>
                            <h5 class="fw-bold">تطبيق موبايل</h5>
                            <p class="text-muted">ادير أكاديميتك من أي مكان عبر التطبيق المحمول</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100 border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-headset fa-3x text-secondary"></i>
                            </div>
                            <h5 class="fw-bold">دعم فني 24/7</h5>
                            <p class="text-muted">فريق دعم متخصص لمساعدتك في أي وقت</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">خطط الأسعار</h2>
                <p class="lead text-muted">اختر الخطة المناسبة لأكاديميتك</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-warning">تجريبية</h5>
                            <div class="price mb-3">
                                <span class="display-4 fw-bold">0</span>
                                <span class="text-muted">ريال/شهر</span>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>7 أيام مجانية</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 50 لاعب</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>إدارة أساسية</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم فني</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-outline-warning w-100">ابدأ التجربة</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-primary shadow h-100">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="fw-bold mb-0">الأكثر شعبية</h5>
                        </div>
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-primary">أساسية</h5>
                            <div class="price mb-3">
                                <span class="display-4 fw-bold">99</span>
                                <span class="text-muted">ريال/شهر</span>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 200 لاعب</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>جميع المميزات</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير متقدمة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم أولوية</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-primary w-100">اختر الخطة</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-success">متقدمة</h5>
                            <div class="price mb-3">
                                <span class="display-4 fw-bold">199</span>
                                <span class="text-muted">ريال/شهر</span>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>لاعبين غير محدود</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>مميزات متقدمة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تكامل مع أنظمة أخرى</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم مخصص</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-outline-success w-100">اختر الخطة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">جاهز لبدء رحلتك مع Vult؟</h2>
            <p class="lead mb-4">انضم إلى آلاف الأكاديميات التي تثق في Vult لإدارة أعمالها</p>
            <a href="/?subdomain=signup" class="btn btn-light btn-lg">
                <i class="fas fa-rocket me-2"></i>ابدأ الآن مجاناً
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-futbol me-2"></i>Vult
                    </h5>
                    <p class="text-muted">منصة شاملة لإدارة الأكاديميات الرياضية، مصممة لمساعدتك على النمو والنجاح.</p>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="fw-bold mb-3">الروابط</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-muted text-decoration-none">المميزات</a></li>
                        <li><a href="#pricing" class="text-muted text-decoration-none">الأسعار</a></li>
                        <li><a href="/sign-in/login.php" class="text-muted text-decoration-none">تسجيل الدخول</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">الدعم</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">مركز المساعدة</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">اتصل بنا</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">الأسئلة الشائعة</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">تابعنا</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <p>&copy; 2024 Vult. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
