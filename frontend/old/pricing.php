<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطط الأسعار - Vult SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .pricing-section { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 80px 0; }
        .pricing-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .pricing-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .btn-primary { background: linear-gradient(45deg, #ff6b35, #f7931e); border: none; }
        .btn-primary:hover { background: linear-gradient(45deg, #e55a2b, #e8841a); }
        .popular-badge { position: absolute; top: -15px; left: 50%; transform: translateX(-50%); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
                        <a class="nav-link" href="/">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#features">المميزات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#pricing">الأسعار</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="/sign-in/login.php" class="btn btn-outline-light me-2">تسجيل الدخول</a>
                    <a href="/?subdomain=signup" class="btn btn-light">ابدأ مجاناً</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing-section">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-dark mb-3">خطط الأسعار</h1>
                <p class="lead text-muted">اختر الخطة المناسبة لأكاديميتك وابدأ رحلتك نحو النجاح</p>
            </div>

            <div class="row justify-content-center">
                <!-- Trial Plan -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card pricing-card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-gift fa-3x text-warning"></i>
                            </div>
                            <h5 class="fw-bold text-warning">تجريبية</h5>
                            <div class="price mb-4">
                                <span class="display-3 fw-bold">0</span>
                                <span class="text-muted">ريال</span>
                                <div class="text-muted small">لمدة 7 أيام</div>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تجربة مجانية لمدة 7 أيام</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 50 لاعب</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>إدارة أساسية للاعبين</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>جدولة بسيطة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير أساسية</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم فني</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-outline-warning w-100">ابدأ التجربة</a>
                        </div>
                    </div>
                </div>

                <!-- Basic Plan -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card pricing-card border-primary shadow h-100 position-relative">
                        <div class="popular-badge">
                            <span class="badge bg-primary px-3 py-2">الأكثر شعبية</span>
                        </div>
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-star fa-3x text-primary"></i>
                            </div>
                            <h5 class="fw-bold text-primary">أساسية</h5>
                            <div class="price mb-4">
                                <span class="display-3 fw-bold">99</span>
                                <span class="text-muted">ريال/شهر</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حتى 200 لاعب</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>جميع مميزات التجربة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>إدارة متقدمة للاعبين</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>جدولة متقدمة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>إدارة المدفوعات</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير شاملة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم أولوية</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-primary w-100">اختر الخطة</a>
                        </div>
                    </div>
                </div>

                <!-- Premium Plan -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card pricing-card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-crown fa-3x text-success"></i>
                            </div>
                            <h5 class="fw-bold text-success">متقدمة</h5>
                            <div class="price mb-4">
                                <span class="display-3 fw-bold">199</span>
                                <span class="text-muted">ريال/شهر</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>لاعبين غير محدود</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>جميع مميزات الأساسية</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>مميزات متقدمة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تكامل مع أنظمة أخرى</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>API متقدم</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تقارير مخصصة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم مخصص</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-outline-success w-100">اختر الخطة</a>
                        </div>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card pricing-card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-building fa-3x text-danger"></i>
                            </div>
                            <h5 class="fw-bold text-danger">مؤسسية</h5>
                            <div class="price mb-4">
                                <span class="display-3 fw-bold">399</span>
                                <span class="text-muted">ريال/شهر</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>لاعبين غير محدود</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>جميع مميزات المتقدمة</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>إدارة متعددة الأكاديميات</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تخصيص كامل</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تدريب مخصص</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>دعم 24/7</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>SLA مضمون</li>
                            </ul>
                            <a href="/?subdomain=signup" class="btn btn-outline-danger w-100">اتصل بنا</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Comparison -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="fw-bold mb-0">مقارنة المميزات</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>المميزة</th>
                                            <th class="text-center">تجريبية</th>
                                            <th class="text-center">أساسية</th>
                                            <th class="text-center">متقدمة</th>
                                            <th class="text-center">مؤسسية</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>عدد اللاعبين</td>
                                            <td class="text-center">50</td>
                                            <td class="text-center">200</td>
                                            <td class="text-center">غير محدود</td>
                                            <td class="text-center">غير محدود</td>
                                        </tr>
                                        <tr>
                                            <td>إدارة اللاعبين</td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>جدولة الحصص</td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>إدارة المدفوعات</td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>التقارير المتقدمة</td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>API متقدم</td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>دعم مخصص</td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold text-center mb-4">الأسئلة الشائعة</h3>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    هل يمكنني تغيير الخطة في أي وقت؟
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، يمكنك ترقية أو تخفيض خطتك في أي وقت. التغييرات ستطبق في بداية دورة الفوترة التالية.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    هل هناك التزام طويل المدى؟
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    لا، يمكنك إلغاء اشتراكك في أي وقت بدون رسوم إضافية. نحن نؤمن بالمرونة والحرية في الاختيار.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    هل البيانات محمية وآمنة؟
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، نستخدم أحدث تقنيات التشفير وحماية البيانات. جميع البيانات محمية وفقاً لأعلى معايير الأمان.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">جاهز للبدء؟</h2>
            <p class="lead mb-4">ابدأ تجربتك المجانية اليوم واكتشف قوة Vult</p>
            <a href="/?subdomain=signup" class="btn btn-light btn-lg">
                <i class="fas fa-rocket me-2"></i>ابدأ مجاناً الآن
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
                        <li><a href="/" class="text-muted text-decoration-none">الرئيسية</a></li>
                        <li><a href="/#features" class="text-muted text-decoration-none">المميزات</a></li>
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
