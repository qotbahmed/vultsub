<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'تسجيل الدخول - Vult';
?>

<!-- Login Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="sports-icon">
                                <i class="fas fa-futbol text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-3">مرحباً بك في Vult</h2>
                            <p class="text-muted fs-5">سجل الدخول للوصول إلى منصتك</p>
                        </div>
                        
                        <!-- Flash Messages -->
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= Yii::$app->session->getFlash('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= Yii::$app->session->getFlash('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['class' => 'form-horizontal'],
                        ]); ?>
                        
                        <?= $form->field($model, 'email')->textInput([
                            'autofocus' => true,
                            'class' => 'form-control',
                            'placeholder' => 'البريد الإلكتروني'
                        ])->label('البريد الإلكتروني', ['class' => 'form-label fw-bold text-dark']) ?>
                        
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'كلمة المرور'
                        ])->label('كلمة المرور', ['class' => 'form-label fw-bold text-dark']) ?>
                        
                        <div class="form-check mb-3">
                            <?= $form->field($model, 'rememberMe')->checkbox([
                                'class' => 'form-check-input',
                                'template' => '<div class="form-check">{input} {label}</div>{error}'
                            ])->label('تذكرني', ['class' => 'form-check-label text-muted']) ?>
                        </div>
                        
                        <div class="d-grid">
                            <?= Html::submitButton('تسجيل الدخول', [
                                'class' => 'btn btn-primary text-white',
                                'name' => 'login-button'
                            ]) ?>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">ليس لديك حساب؟ 
                                <a href="<?= Url::to(['auth/register']) ?>" class="text-decoration-none fw-bold" style="color: #ff6b35;">سجل الآن</a>
                            </p>
                        </div>
                        
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.sports-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.form-control {
    border-radius: 15px;
    border: 2px solid #e9ecef;
    padding: 12px 20px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    border: none;
    border-radius: 25px;
    padding: 15px 40px;
    font-weight: 600;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
}

.form-check-input:checked {
    background-color: #ff6b35;
    border-color: #ff6b35;
}

.form-check-input:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
}
</style>

<script>
document.getElementById('login-form').addEventListener('submit', function(event) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const btnText = submitBtn.textContent;
    
    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري تسجيل الدخول...';
    submitBtn.disabled = true;
});
</script>