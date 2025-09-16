<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Vult SaaS - منصة إدارة الأكاديميات الرياضية';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-white mb-4">
                    منصة شاملة لإدارة الأكاديميات الرياضية
                </h1>
                <p class="lead text-white mb-4">
                    احصل على جميع الأدوات التي تحتاجها لإدارة أكاديميتك بكفاءة عالية. 
                    من إدارة اللاعبين إلى الجدولة والتقارير، كل شيء في مكان واحد.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= Url::to(['auth/register']) ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i>ابدأ مجاناً
                    </a>
                    <a href="<?= Url::to(['home/pricing']) ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-tag me-2"></i>عرض الأسعار
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-futbol" style="font-size: 15rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">لماذا تختار Vult؟</h2>
            <p class="lead text-muted">أدوات متقدمة مصممة خصيصاً للأكاديميات الرياضية</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">إدارة اللاعبين</h5>
                    <p class="text-muted">إدارة شاملة لبيانات اللاعبين، المتابعة، والتقييمات</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-3">الجدولة الذكية</h5>
                    <p class="text-muted">جدولة تلقائية للحصص والتدريبات مع إشعارات ذكية</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-info"></i>
                    </div>
                    <h5 class="fw-bold mb-3">التقارير المتقدمة</h5>
                    <p class="text-muted">تقارير شاملة عن الأداء والإحصائيات المالية</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-3">تطبيق موبايل</h5>
                    <p class="text-muted">تطبيق للاعبين وأولياء الأمور لمتابعة التقدم</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="mb-3">
                        <i class="fas fa-credit-card fa-3x text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-3">إدارة المدفوعات</h5>
                    <p class="text-muted">نظام دفع متكامل مع تتبع الاشتراكات</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-secondary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">أمان عالي</h5>
                    <p class="text-muted">حماية متقدمة للبيانات مع تشفير SSL</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Preview Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">خطط مرنة تناسب احتياجاتك</h2>
            <p class="lead text-muted">ابدأ مجاناً وترقى عندما تكون جاهزاً</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title text-warning">تجريبية</h5>
                                <div class="h2 fw-bold">0 ريال</div>
                                <p class="text-muted">7 أيام مجاناً</p>
                                <ul class="list-unstyled">
                                    <li>حتى 50 لاعب</li>
                                    <li>مميزات أساسية</li>
                                    <li>دعم فني</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">أساسية</h5>
                                <div class="h2 fw-bold">99 ريال/شهر</div>
                                <p class="text-muted">الأكثر شعبية</p>
                                <ul class="list-unstyled">
                                    <li>حتى 200 لاعب</li>
                                    <li>جميع المميزات</li>
                                    <li>دعم أولوية</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title text-success">متقدمة</h5>
                                <div class="h2 fw-bold">199 ريال/شهر</div>
                                <p class="text-muted">للمحترفين</p>
                                <ul class="list-unstyled">
                                    <li>لاعبين غير محدود</li>
                                    <li>مميزات متقدمة</li>
                                    <li>دعم مخصص</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?= Url::to(['home/pricing']) ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-tag me-2"></i>عرض جميع الخطط
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-3">جاهز للبدء؟</h2>
        <p class="lead mb-4">انضم إلى آلاف الأكاديميات التي تثق في Vult</p>
        <a href="<?= Url::to(['auth/register']) ?>" class="btn btn-light btn-lg">
            <i class="fas fa-rocket me-2"></i>ابدأ تجربتك المجانية الآن
        </a>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">تواصل معنا</h2>
            <p class="lead text-muted">نحن هنا لمساعدتك في كل خطوة</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                        <h5>البريد الإلكتروني</h5>
                        <p class="text-muted">info@vult.com</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                        <h5>الهاتف</h5>
                        <p class="text-muted">+966 50 123 4567</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h5>الموقع</h5>
                        <p class="text-muted">الرياض، المملكة العربية السعودية</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.feature-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
}

.hero-image {
    position: relative;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}
</style>