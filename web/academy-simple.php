<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الأكاديمية - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="sports" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23sports)"/></svg>');
            opacity: 0.3;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 20px;
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        
        .sidebar .nav-link:hover::before {
            left: 100%;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }
        
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .trial-banner {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .trial-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .sports-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .sports-icon.primary { background: linear-gradient(45deg, #1e3c72, #2a5298); }
        .sports-icon.success { background: linear-gradient(45deg, #28a745, #20c997); }
        .sports-icon.warning { background: linear-gradient(45deg, #ffc107, #fd7e14); }
        .sports-icon.info { background: linear-gradient(45deg, #17a2b8, #6f42c1); }
        
        .activity-item {
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .quick-action-btn {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .quick-action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .quick-action-btn:hover::before {
            left: 100%;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }
        
        .academy-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0 position-relative">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="academy-logo me-3">V</div>
                        <h4 class="text-white mb-0">أكاديمية النصر</h4>
                    </div>
                </div>
                <nav class="nav flex-column px-3 position-relative">
                    <a class="nav-link active" href="#dashboard">
                        <i class="fas fa-tachometer-alt me-3"></i>لوحة التحكم
                    </a>
                    <a class="nav-link" href="#players">
                        <i class="fas fa-users me-3"></i>إدارة اللاعبين
                    </a>
                    <a class="nav-link" href="#schedule">
                        <i class="fas fa-calendar me-3"></i>الجدولة
                    </a>
                    <a class="nav-link" href="#teams">
                        <i class="fas fa-users-cog me-3"></i>الفريق
                    </a>
                    <a class="nav-link" href="#payments">
                        <i class="fas fa-credit-card me-3"></i>المدفوعات
                    </a>
                    <a class="nav-link" href="#reports">
                        <i class="fas fa-chart-line me-3"></i>التقارير
                    </a>
                    <a class="nav-link" href="#skills">
                        <i class="fas fa-trophy me-3"></i>تقييم المهارات
                    </a>
                    <a class="nav-link" href="#settings">
                        <i class="fas fa-cog me-3"></i>الإعدادات
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <!-- Trial Banner -->
                <div class="trial-banner position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2"><i class="fas fa-clock me-2"></i>حساب تجريبي</h5>
                            <p class="mb-0">لديك 5 أيام متبقية في التجربة المجانية. <a href="?subdomain=pricing" class="text-white text-decoration-underline fw-bold">ترقية الآن</a> للاستمرار في استخدام جميع المميزات.</p>
                        </div>
                        <button class="btn btn-light btn-sm" onclick="window.location.href='?subdomain=pricing'">
                            <i class="fas fa-crown me-1"></i>ترقية
                        </button>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">إجمالي اللاعبين</h6>
                                    <h3 class="mb-0 text-primary">127</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i>+12 هذا الشهر</small>
                                </div>
                                <div class="sports-icon primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">الأنشطة النشطة</h6>
                                    <h3 class="mb-0 text-success">23</h3>
                                    <small class="text-success"><i class="fas fa-calendar-check me-1"></i>هذا الأسبوع</small>
                                </div>
                                <div class="sports-icon success">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">الإيرادات الشهرية</h6>
                                    <h3 class="mb-0 text-warning">2,450</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i>+8% من الشهر الماضي</small>
                                </div>
                                <div class="sports-icon warning">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">معدل الحضور</h6>
                                    <h3 class="mb-0 text-info">94%</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i>+2% من الأسبوع الماضي</small>
                                </div>
                                <div class="sports-icon info">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity and Quick Actions -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="stat-card">
                            <h5 class="mb-4"><i class="fas fa-history me-2"></i>النشاط الأخير</h5>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-user-plus text-success me-2"></i>
                                        <strong>لاعب جديد مسجل:</strong> أحمد محمد
                                    </div>
                                    <small class="text-muted">منذ ساعتين</small>
                                </div>
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-credit-card text-primary me-2"></i>
                                        <strong>دفعة مستلمة:</strong> 150 ريال من سارة علي
                                    </div>
                                    <small class="text-muted">منذ 4 ساعات</small>
                                </div>
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-calendar text-warning me-2"></i>
                                        <strong>نشاط مجدول:</strong> تدريب كرة القدم - غداً 4:00 م
                                    </div>
                                    <small class="text-muted">منذ 6 ساعات</small>
                                </div>
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-trophy text-info me-2"></i>
                                        <strong>تقييم مهارات:</strong> محمد أحمد - كرة القدم
                                    </div>
                                    <small class="text-muted">منذ 8 ساعات</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="stat-card">
                            <h5 class="mb-4"><i class="fas fa-bolt me-2"></i>إجراءات سريعة</h5>
                            <div class="d-grid gap-2">
                                <button class="quick-action-btn" onclick="alert('سيتم تنفيذ هذه الميزة قريباً')">
                                    <i class="fas fa-user-plus me-2"></i>إضافة لاعب جديد
                                </button>
                                <button class="quick-action-btn" onclick="alert('سيتم تنفيذ هذه الميزة قريباً')">
                                    <i class="fas fa-calendar-plus me-2"></i>جدولة نشاط
                                </button>
                                <button class="quick-action-btn" onclick="alert('سيتم تنفيذ هذه الميزة قريباً')">
                                    <i class="fas fa-credit-card me-2"></i>تسجيل دفعة
                                </button>
                                <button class="quick-action-btn" onclick="alert('سيتم تنفيذ هذه الميزة قريباً')">
                                    <i class="fas fa-chart-bar me-2"></i>عرض التقارير
                                </button>
                                <button class="quick-action-btn" onclick="alert('سيتم تنفيذ هذه الميزة قريباً')">
                                    <i class="fas fa-trophy me-2"></i>تقييم المهارات
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
