<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأسعار - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .pricing-hero {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .pricing-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="sports" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23sports)"/></svg>');
            opacity: 0.3;
        }
        
        .pricing-card {
            border: 2px solid #e9ecef;
            border-radius: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            background: white;
        }
        
        .pricing-card:hover {
            border-color: #ff6b35;
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.2);
        }
        
        .pricing-card.featured {
            border-color: #ff6b35;
            background: linear-gradient(135deg, #fff 0%, #fff8f5 100%);
            transform: scale(1.05);
        }
        
        .pricing-card.featured::before {
            content: 'الأكثر شعبية';
            position: absolute;
            top: 20px;
            right: -30px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            color: white;
            padding: 8px 40px;
            font-size: 12px;
            font-weight: 600;
            transform: rotate(45deg);
            z-index: 10;
        }
        
        .price-display {
            font-size: 3rem;
            font-weight: 900;
            color: #1e3c72;
            line-height: 1;
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
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
            position: relative;
            padding-right: 30px;
        }
        
        .feature-list li:last-child {
            border-bottom: none;
        }
        
        .feature-list li i {
            color: #28a745;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
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
        
        .faq-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .faq-header {
            background: #f8f9fa;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq-header:hover {
            background: #e9ecef;
        }
        
        .faq-body {
            padding: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="pricing-hero text-white position-relative">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="sports-icon">
                        <i class="fas fa-trophy fa-2x text-white"></i>
                    </div>
                    <h1 class="display-4 font-weight-bold mb-4">اختر الخطة المناسبة لأكاديميتك</h1>
                    <p class="lead mb-4">
                        خطط مرنة ومصممة خصيصاً للأكاديميات الرياضية. ابدأ مجاناً وارتقِ حسب احتياجاتك
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Plans -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">خطط الأسعار</h2>
                    <p class="lead text-muted">
                        جميع الخطط تشمل المميزات الأساسية. اختر ما يناسب حجم أكاديميتك
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <!-- Starter Plan -->
                        <div class="col-lg-4 mb-4">
                            <div class="pricing-card h-100 p-4 text-center">
                                <h5 class="font-weight-bold mb-3">البداية</h5>
                                <div class="price-display text-primary mb-2">99</div>
                                <div class="text-muted mb-4">ريال/شهر</div>
                                
                                <ul class="feature-list text-start mb-4">
                                    <li><i class="fas fa-check"></i> حتى فرعين</li>
                                    <li><i class="fas fa-check"></i> حتى 100 لاعب</li>
                                    <li><i class="fas fa-check"></i> إدارة اللاعبين الأساسية</li>
                                    <li><i class="fas fa-check"></i> جدولة الأنشطة</li>
                                    <li><i class="fas fa-check"></i> تقارير أساسية</li>
                                    <li><i class="fas fa-check"></i> دعم بالبريد الإلكتروني</li>
                                    <li><i class="fas fa-check"></i> تطبيق موبايل أساسي</li>
                                </ul>
                                
                                <a href="?subdomain=signup" class="btn btn-outline-primary btn-lg w-100 mb-3">ابدأ التجربة المجانية</a>
                                <small class="text-muted">7 أيام مجانية</small>
                            </div>
                        </div>
                        
                        <!-- Professional Plan -->
                        <div class="col-lg-4 mb-4">
                            <div class="pricing-card featured h-100 p-4 text-center">
                                <h5 class="font-weight-bold mb-3">المهنية</h5>
                                <div class="price-display text-primary mb-2">299</div>
                                <div class="text-muted mb-4">ريال/شهر</div>
                                
                                <ul class="feature-list text-start mb-4">
                                    <li><i class="fas fa-check"></i> حتى 5 فروع</li>
                                    <li><i class="fas fa-check"></i> حتى 500 لاعب</li>
                                    <li><i class="fas fa-check"></i> إدارة شاملة للاعبين</li>
                                    <li><i class="fas fa-check"></i> جدولة متقدمة</li>
                                    <li><i class="fas fa-check"></i> تقارير متقدمة</li>
                                    <li><i class="fas fa-check"></i> تقييم المهارات</li>
                                    <li><i class="fas fa-check"></i> دعم أولوية</li>
                                    <li><i class="fas fa-check"></i> تطبيق موبايل كامل</li>
                                    <li><i class="fas fa-check"></i> إشعارات SMS</li>
                                </ul>
                                
                                <a href="?subdomain=signup" class="btn btn-sports btn-lg w-100 mb-3">ابدأ التجربة المجانية</a>
                                <small class="text-muted">7 أيام مجانية</small>
                            </div>
                        </div>
                        
                        <!-- Enterprise Plan -->
                        <div class="col-lg-4 mb-4">
                            <div class="pricing-card h-100 p-4 text-center">
                                <h5 class="font-weight-bold mb-3">المؤسسية</h5>
                                <div class="price-display text-primary mb-2">599</div>
                                <div class="text-muted mb-4">ريال/شهر</div>
                                
                                <ul class="feature-list text-start mb-4">
                                    <li><i class="fas fa-check"></i> فروع غير محدودة</li>
                                    <li><i class="fas fa-check"></i> لاعبين غير محدود</li>
                                    <li><i class="fas fa-check"></i> جميع مميزات المهنية</li>
                                    <li><i class="fas fa-check"></i> تقارير مخصصة</li>
                                    <li><i class="fas fa-check"></i> تحليلات متقدمة</li>
                                    <li><i class="fas fa-check"></i> دعم 24/7</li>
                                    <li><i class="fas fa-check"></i> واجهة برمجة API</li>
                                    <li><i class="fas fa-check"></i> تكاملات مخصصة</li>
                                    <li><i class="fas fa-check"></i> تدريب مخصص</li>
                                </ul>
                                
                                <a href="?subdomain=contact" class="btn btn-outline-primary btn-lg w-100 mb-3">تواصل مع المبيعات</a>
                                <small class="text-muted">تجربة مخصصة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Comparison -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">مقارنة المميزات</h2>
                    <p class="lead text-muted">
                        اكتشف الفرق بين الخطط واختر الأنسب لأكاديميتك
                    </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>المميزة</th>
                                    <th class="text-center">البداية</th>
                                    <th class="text-center">المهنية</th>
                                    <th class="text-center">المؤسسية</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>عدد الفروع</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">5</td>
                                    <td class="text-center">غير محدود</td>
                                </tr>
                                <tr>
                                    <td>عدد اللاعبين</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">500</td>
                                    <td class="text-center">غير محدود</td>
                                </tr>
                                <tr>
                                    <td>إدارة اللاعبين</td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>جدولة الأنشطة</td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>تقييم المهارات</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>التقارير المتقدمة</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>واجهة برمجة API</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>الدعم الفني</td>
                                    <td class="text-center">بريد إلكتروني</td>
                                    <td class="text-center">أولوية</td>
                                    <td class="text-center">24/7</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 font-weight-bold mb-3 section-title">الأسئلة الشائعة</h2>
                    <p class="lead text-muted">
                        إجابات على أكثر الأسئلة شيوعاً حول خطط الأسعار
                    </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="faq-item">
                        <div class="faq-header" onclick="toggleFaq(this)">
                            <h6 class="mb-0">هل يمكنني تغيير خطتي في أي وقت؟</h6>
                        </div>
                        <div class="faq-body">
                            <p>نعم، يمكنك ترقية أو تخفيض خطتك في أي وقت. التغييرات ستطبق في بداية دورة الفوترة التالية.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-header" onclick="toggleFaq(this)">
                            <h6 class="mb-0">ماذا يحدث بعد انتهاء التجربة المجانية؟</h6>
                        </div>
                        <div class="faq-body">
                            <p>بعد انتهاء الـ 7 أيام المجانية، يمكنك اختيار خطة مدفوعة أو إلغاء الحساب. لن يتم خصم أي مبلغ بدون موافقتك.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-header" onclick="toggleFaq(this)">
                            <h6 class="mb-0">هل يمكنني إلغاء اشتراكي في أي وقت؟</h6>
                        </div>
                        <div class="faq-body">
                            <p>نعم، يمكنك إلغاء اشتراكك في أي وقت. لن يتم تجديد الاشتراك تلقائياً بعد انتهاء الفترة المدفوعة.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-header" onclick="toggleFaq(this)">
                            <h6 class="mb-0">هل توجد رسوم إعداد أو إلغاء؟</h6>
                        </div>
                        <div class="faq-body">
                            <p>لا، لا توجد رسوم إعداد أو إلغاء. تدفع فقط مقابل الخطة التي تختارها شهرياً.</p>
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
                    <h2 class="display-5 font-weight-bold mb-3">جاهز للبدء؟</h2>
                    <p class="lead mb-4">
                        ابدأ تجربتك المجانية اليوم واكتشف كيف يمكن لـ Vult أن يحول أكاديميتك
                    </p>
                    <a href="?subdomain=signup" class="btn btn-sports btn-lg px-5 py-3">
                        <i class="fas fa-rocket me-2"></i>ابدأ التجربة المجانية الآن
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFaq(header) {
            const body = header.nextElementSibling;
            const isOpen = body.style.display === 'block';
            
            // Close all FAQ items
            document.querySelectorAll('.faq-body').forEach(item => {
                item.style.display = 'none';
            });
            
            // Toggle current item
            if (!isOpen) {
                body.style.display = 'block';
            }
        }
    </script>
</body>
</html>
