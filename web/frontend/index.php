<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vult - منصة إدارة الأكاديميات الرياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
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
        
        .sports-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #f7931e, #1e3c72);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .btn-sports {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-sports::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-sports:hover::before {
            left: 100%;
        }
        
        .btn-sports:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .pricing-card {
            border: 2px solid #e9ecef;
            border-radius: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .pricing-card:hover {
            border-color: #ff6b35;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 107, 53, 0.2);
        }
        
        .pricing-card.featured {
            border-color: #ff6b35;
            background: linear-gradient(135deg, #fff 0%, #fff8f5 100%);
        }
        
        .pricing-card.featured::before {
            content: 'الأكثر شعبية';
            position: absolute;
            top: 20px;
            right: -30px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            color: white;
            padding: 5px 40px;
            font-size: 12px;
            font-weight: 600;
            transform: rotate(45deg);
        }
        
        .sport-badge {
            display: inline-block;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .floating-elements .element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-elements .element:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-elements .element:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .floating-elements .element:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 50px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }
        
        .admin-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
        }
        
        .admin-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            color: inherit;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="floating-elements">
            <div class="element"><i class="fas fa-futbol fa-3x"></i></div>
            <div class="element"><i class="fas fa-basketball-ball fa-3x"></i></div>
            <div class="element"><i class="fas fa-volleyball-ball fa-3x"></i></div>
        </div>
        
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="sports-icon">
                        <i class="fas fa-trophy fa-2x text-white"></i>
                    </div>
                    <h1 class="display-3 font-weight-bold mb-4">
                        أطلق إمكانيات أكاديميتك الرياضية
                    </h1>
                    <p class="lead mb-4 fs-5">
                        منصة شاملة لإدارة الأكاديميات الرياضية، تدريب اللاعبين، وتتبع الأداء. 
                        احترافية في كل تفصيل، شغف في كل لحظة.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <a href="?subdomain=signup" class="btn btn-sports btn-lg px-5 py-3">
                            <i class="fas fa-rocket me-2"></i>ابدأ التجربة المجانية
                        </a>
                        <a href="?subdomain=pricing" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="fas fa-tag me-2"></i>عرض الأسعار
                        </a>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stats-card">
                                <h3 class="mb-1">7</h3>
                                <small>أيام تجربة مجانية</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-card">
                                <h3 class="mb-1">100%</h3>
                                <small>بدون بطاقة ائتمان</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-card">
                                <h3 class="mb-1">24/7</h3>
                                <small>دعم فني</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="bg-white rounded-4 shadow-lg p-4 position-relative">
                            <h5 class="text-dark mb-4">لوحة تحكم الأكاديمية</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="bg-primary text-white p-3 rounded-3 mb-2">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <div class="fw-bold">اللاعبين</div>
                                        <small>127 لاعب</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-success text-white p-3 rounded-3 mb-2">
                                        <i class="fas fa-calendar fa-2x mb-2"></i>
                                        <div class="fw-bold">الجدولة</div>
                                        <small>23 نشاط</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-warning text-white p-3 rounded-3 mb-2">
                                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                                        <div class="fw-bold">التقارير</div>
                                        <small>أداء متقدم</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-info text-white p-3 rounded-3 mb-2">
                                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                                        <div class="fw-bold">المدفوعات</div>
                                        <small>2,450 ريال</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Admin Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">لوحة الإدارة</h2>
                    <p class="lead text-muted">
                        أدوات إدارية متقدمة لإدارة النظام والطلبات
                    </p>
                </div>
            </div>
            
            <div class="admin-section">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <a href="academy-requests/" class="admin-card d-block">
                            <div class="text-center">
                                <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                                <h5 class="fw-bold">إدارة طلبات الأكاديميات</h5>
                                <p class="text-muted">مراجعة وموافقة طلبات انضمام الأكاديميات الجديدة</p>
                                <span class="badge bg-warning">24 طلب في الانتظار</span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <a href="players-management/" class="admin-card d-block">
                            <div class="text-center">
                                <i class="fas fa-users fa-3x text-success mb-3"></i>
                                <h5 class="fw-bold">إدارة اللاعبين</h5>
                                <p class="text-muted">إدارة بيانات اللاعبين والاشتراكات</p>
                                <span class="badge bg-primary">127 لاعب نشط</span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <a href="?subdomain=academy" class="admin-card d-block">
                            <div class="text-center">
                                <i class="fas fa-tachometer-alt fa-3x text-info mb-3"></i>
                                <h5 class="fw-bold">لوحة تحكم الأكاديمية</h5>
                                <p class="text-muted">إدارة شاملة للأكاديمية والأنشطة</p>
                                <span class="badge bg-success">نشط</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sports Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">الرياضات المدعومة</h2>
                    <p class="lead text-muted">
                        نقدم دعم شامل لجميع أنواع الرياضات والأكاديميات التدريبية
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center">
                        <span class="sport-badge">كرة القدم</span>
                        <span class="sport-badge">كرة السلة</span>
                        <span class="sport-badge">كرة الطائرة</span>
                        <span class="sport-badge">التنس</span>
                        <span class="sport-badge">السباحة</span>
                        <span class="sport-badge">الجمباز</span>
                        <span class="sport-badge">الرياضات القتالية</span>
                        <span class="sport-badge">ألعاب القوى</span>
                        <span class="sport-badge">والمزيد...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">كل ما تحتاجه لإدارة أكاديميتك</h2>
                    <p class="lead text-muted">
                        منصة شاملة توفر جميع الأدوات اللازمة لإدارة أكاديميتك الرياضية بكفاءة ونمو مستمر
                    </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                        <div class="sports-icon mb-3">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">إدارة اللاعبين</h5>
                        <p class="text-muted">ملفات شاملة للاعبين، تتبع الحضور، مراقبة التقدم، والتواصل مع أولياء الأمور</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                        <div class="sports-icon mb-3">
                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">إدارة الجدولة</h5>
                        <p class="text-muted">إنشاء وإدارة جداول التدريب، تعيين المدربين، وحجز المرافق بسهولة</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                        <div class="sports-icon mb-3">
                            <i class="fas fa-credit-card fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">إدارة المدفوعات</h5>
                        <p class="text-muted">استقبال المدفوعات، إدارة الاشتراكات، إصدار الفواتير، وتتبع الأداء المالي</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                        <div class="sports-icon mb-3">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">تقارير الأداء</h5>
                        <p class="text-muted">تقارير شاملة عن أداء اللاعبين، الإحصائيات المالية، وتتبع التقدم</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                        <div class="sports-icon mb-3">
                            <i class="fas fa-trophy fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">تقييم المهارات</h5>
                        <p class="text-muted">تقييم شامل لمهارات اللاعبين، تتبع التطور، وإصدار الشهادات</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                        <div class="sports-icon mb-3">
                            <i class="fas fa-mobile-alt fa-2x text-white"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">تطبيق موبايل</h5>
                        <p class="text-muted">تطبيق موبايل للاعبين وأولياء الأمور لمتابعة التقدم والجدولة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">أسعار بسيطة وشفافة</h2>
                    <p class="lead text-muted">
                        اختر الخطة التي تناسب احتياجات أكاديميتك. جميع الخطط تشمل مميزاتنا الأساسية
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="pricing-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                                <h5 class="font-weight-bold mb-3">البداية</h5>
                                <div class="price mb-3">
                                    <span class="display-4 font-weight-bold text-primary">99</span>
                                    <span class="text-muted">ريال/شهر</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى فرعين</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 100 لاعب</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير أساسية</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم بالبريد الإلكتروني</li>
                                </ul>
                                <a href="?subdomain=signup" class="btn btn-outline-primary btn-lg w-100">ابدأ التجربة المجانية</a>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="pricing-card featured h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                                <h5 class="font-weight-bold mb-3">المهنية</h5>
                                <div class="price mb-3">
                                    <span class="display-4 font-weight-bold text-primary">299</span>
                                    <span class="text-muted">ريال/شهر</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 5 فروع</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 500 لاعب</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير متقدمة</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم أولوية</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تطبيق موبايل</li>
                                </ul>
                                <a href="?subdomain=signup" class="btn btn-sports btn-lg w-100">ابدأ التجربة المجانية</a>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="pricing-card h-100 p-4 text-center bg-white rounded-4 shadow-sm">
                                <h5 class="font-weight-bold mb-3">المؤسسية</h5>
                                <div class="price mb-3">
                                    <span class="display-4 font-weight-bold text-primary">599</span>
                                    <span class="text-muted">ريال/شهر</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>فروع غير محدودة</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>لاعبين غير محدود</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير مخصصة</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم 24/7</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>واجهة برمجة</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تكاملات مخصصة</li>
                                </ul>
                                <a href="?subdomain=contact" class="btn btn-outline-primary btn-lg w-100">تواصل مع المبيعات</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h2 class="display-5 font-weight-bold mb-3">جاهز لتحويل أكاديميتك؟</h2>
                    <p class="lead mb-4">
                        انضم إلى آلاف الأكاديميات التي تستخدم Vult لإدارة عملياتها بكفاءة أكبر
                    </p>
                    <a href="?subdomain=signup" class="btn btn-sports btn-lg px-5 py-3">
                        <i class="fas fa-rocket me-2"></i>ابدأ تجربتك المجانية اليوم
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
