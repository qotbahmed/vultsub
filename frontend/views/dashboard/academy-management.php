<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'إدارة الأكاديمية - Vult';
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
        
        .main-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #ff6b35;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
        }
        
        .btn-warning {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        
        .academy-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">
                        <i class="fas fa-building me-3"></i>
                        إدارة الأكاديمية
                    </h1>
                    <p class="mb-0 mt-2">إدارة معلومات وبيانات الأكاديمية</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="<?= Url::to(['dashboard/trial-dashboard']) ?>" class="btn btn-light me-2">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للوحة التحكم
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAcademyModal">
                        <i class="fas fa-edit me-2"></i>
                        تعديل المعلومات
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">150</h3>
                    <p class="mb-0">إجمالي اللاعبين</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">5</h3>
                    <p class="mb-0">عدد الفروع</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">12</h3>
                    <p class="mb-0">الرياضات المتاحة</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="mb-1">85%</h3>
                    <p class="mb-0">معدل الرضا</p>
                </div>
            </div>
        </div>

        <!-- Academy Information -->
        <div class="main-card">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="academy-logo mb-3">
                        <i class="fas fa-building"></i>
                    </div>
                    <h4>أكاديمية الرياضة المتميزة</h4>
                    <p class="text-muted">أكاديمية متخصصة في تدريب الرياضيين</p>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    البريد الإلكتروني
                                </h6>
                                <p class="mb-0">info@academy.com</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    رقم الهاتف
                                </h6>
                                <p class="mb-0">+966 50 123 4567</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    العنوان
                                </h6>
                                <p class="mb-0">الرياض، المملكة العربية السعودية</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-calendar me-2"></i>
                                    تاريخ التأسيس
                                </h6>
                                <p class="mb-0">2020</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academy Details -->
        <div class="row">
            <div class="col-md-6">
                <div class="main-card">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الأكاديمية
                    </h5>
                    <div class="info-card">
                        <h6 class="fw-bold mb-2">الوصف</h6>
                        <p class="mb-0">أكاديمية متخصصة في تدريب الرياضيين في مختلف الرياضات، مع فريق من المدربين المحترفين والمرافق الحديثة.</p>
                    </div>
                    <div class="info-card">
                        <h6 class="fw-bold mb-2">الرياضات المتاحة</h6>
                        <div class="d-flex flex-wrap">
                            <span class="badge bg-primary me-2 mb-2">كرة القدم</span>
                            <span class="badge bg-primary me-2 mb-2">كرة السلة</span>
                            <span class="badge bg-primary me-2 mb-2">التنس</span>
                            <span class="badge bg-primary me-2 mb-2">السباحة</span>
                            <span class="badge bg-primary me-2 mb-2">ألعاب القوى</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="main-card">
                    <h5 class="mb-3">
                        <i class="fas fa-cogs me-2"></i>
                        إعدادات الأكاديمية
                    </h5>
                    <div class="info-card">
                        <h6 class="fw-bold mb-2">ساعات العمل</h6>
                        <p class="mb-0">السبت - الخميس: 6:00 ص - 10:00 م</p>
                        <p class="mb-0">الجمعة: 2:00 م - 10:00 م</p>
                    </div>
                    <div class="info-card">
                        <h6 class="fw-bold mb-2">حالة الاشتراك</h6>
                        <span class="badge bg-success">نشط</span>
                        <p class="mb-0 mt-2">ينتهي في: 15 مارس 2025</p>
                    </div>
                    <div class="info-card">
                        <h6 class="fw-bold mb-2">الخطة الحالية</h6>
                        <p class="mb-0">الخطة الأساسية - 150 لاعب</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Academy Modal -->
    <div class="modal fade" id="editAcademyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        تعديل معلومات الأكاديمية
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">اسم الأكاديمية</label>
                                <input type="text" class="form-control" value="أكاديمية الرياضة المتميزة">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" value="info@academy.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" value="+966 50 123 4567">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">العنوان</label>
                                <input type="text" class="form-control" value="الرياض، المملكة العربية السعودية">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea class="form-control" rows="4">أكاديمية متخصصة في تدريب الرياضيين في مختلف الرياضات، مع فريق من المدربين المحترفين والمرافق الحديثة.</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الرياضات المتاحة</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="football" checked>
                                        <label class="form-check-label" for="football">كرة القدم</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="basketball" checked>
                                        <label class="form-check-label" for="basketball">كرة السلة</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tennis" checked>
                                        <label class="form-check-label" for="tennis">التنس</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="swimming" checked>
                                        <label class="form-check-label" for="swimming">السباحة</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="athletics">
                                        <label class="form-check-label" for="athletics">ألعاب القوى</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
