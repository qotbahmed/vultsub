<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'تسجيل جديد - Vult';
?>

<!-- Register Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="sports-icon">
                                <i class="fas fa-futbol text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-3">انضم إلى Vult</h2>
                            <p class="text-muted fs-5">سجل أكاديميتك وابدأ تجربتك المجانية</p>
                        </div>
                        
                        <!-- Flash Messages -->
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= Yii::$app->session->getFlash('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= Yii::$app->session->getFlash('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php $form = ActiveForm::begin([
                            'id' => 'register-form',
                            'options' => ['class' => 'form-horizontal'],
                        ]); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'academy_name')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'اسم الأكاديمية'
                                ])->label('اسم الأكاديمية *', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'manager_name')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'اسم المدير'
                                ])->label('اسم المدير *', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput([
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'placeholder' => 'البريد الإلكتروني'
                                ])->label('البريد الإلكتروني *', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone')->textInput([
                                    'type' => 'tel',
                                    'class' => 'form-control',
                                    'placeholder' => 'رقم الهاتف (مثال: +966501234567)',
                                    'pattern' => '[\+]?[0-9\s\-\(\)]{7,20}',
                                    'title' => 'أدخل رقم هاتف صحيح (7-20 رقم)',
                                    'maxlength' => 20
                                ])->label('رقم الهاتف *', ['class' => 'form-label fw-bold text-dark']) ?>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    يمكنك إدخال رقم الهاتف بأي تنسيق: +966501234567 أو 0501234567
                                </small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'city')->textInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'المدينة'
                                ])->label('المدينة *', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'branches_count')->textInput([
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'value' => 1,
                                    'min' => 1
                                ])->label('عدد الفروع', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">الرياضات المقدمة *</label>
                            <div class="row" id="sportsContainer">
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="كرة القدم">
                                        <i class="fas fa-futbol me-2"></i>كرة القدم
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="كرة السلة">
                                        <i class="fas fa-basketball-ball me-2"></i>كرة السلة
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="كرة الطائرة">
                                        <i class="fas fa-volleyball-ball me-2"></i>كرة الطائرة
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="التنس">
                                        <i class="fas fa-table-tennis me-2"></i>التنس
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="السباحة">
                                        <i class="fas fa-swimmer me-2"></i>السباحة
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="الجمباز">
                                        <i class="fas fa-dumbbell me-2"></i>الجمباز
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="الرياضات القتالية">
                                        <i class="fas fa-fist-raised me-2"></i>الرياضات القتالية
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="sport-checkbox" onclick="toggleSport(this)">
                                        <input type="checkbox" name="sports[]" value="أخرى">
                                        <i class="fas fa-plus me-2"></i>أخرى
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <?= $form->field($model, 'description')->textarea([
                                'class' => 'form-control',
                                'rows' => 3,
                                'placeholder' => 'وصف الأكاديمية'
                            ])->label('وصف الأكاديمية', ['class' => 'form-label fw-bold text-dark']) ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'password')->passwordInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'كلمة المرور'
                                ])->label('كلمة المرور *', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'confirm_password')->passwordInput([
                                    'class' => 'form-control',
                                    'placeholder' => 'تأكيد كلمة المرور'
                                ])->label('تأكيد كلمة المرور *', ['class' => 'form-label fw-bold text-dark']) ?>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <?= Html::submitButton('تسجيل الأكاديمية', [
                                'class' => 'btn btn-primary text-white',
                                'name' => 'register-button'
                            ]) ?>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">لديك حساب بالفعل؟ 
                                <a href="<?= Url::to(['auth/login']) ?>" class="text-decoration-none fw-bold" style="color: #ff6b35;">سجل الدخول</a>
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

/* Phone validation styles */
.form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
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

.sport-checkbox {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 10px;
    margin: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sport-checkbox:hover {
    background: #e9ecef;
}

.sport-checkbox.selected {
    background: #ff6b35;
    color: white;
    border-color: #ff6b35;
}

.sport-checkbox input[type="checkbox"] {
    display: none;
}
</style>

<script>
function toggleSport(element) {
    const checkbox = element.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        element.classList.add('selected');
    } else {
        element.classList.remove('selected');
    }
}

// Phone validation
document.querySelector('input[name="RegisterForm[phone]"]').addEventListener('input', function(e) {
    const phone = e.target.value;
    const phonePattern = /^[\+]?[0-9\s\-\(\)]{7,20}$/;
    const phoneField = e.target.closest('.form-group');
    const errorElement = phoneField.querySelector('.invalid-feedback') || document.createElement('div');
    
    if (phone && !phonePattern.test(phone)) {
        errorElement.className = 'invalid-feedback';
        errorElement.textContent = 'رقم الهاتف غير صحيح. يجب أن يحتوي على 7-20 رقم';
        e.target.classList.add('is-invalid');
        e.target.classList.remove('is-valid');
        if (!phoneField.querySelector('.invalid-feedback')) {
            phoneField.appendChild(errorElement);
        }
    } else if (phone && phonePattern.test(phone)) {
        e.target.classList.remove('is-invalid');
        e.target.classList.add('is-valid');
        if (phoneField.querySelector('.invalid-feedback')) {
            phoneField.querySelector('.invalid-feedback').remove();
        }
    } else {
        e.target.classList.remove('is-invalid', 'is-valid');
        if (phoneField.querySelector('.invalid-feedback')) {
            phoneField.querySelector('.invalid-feedback').remove();
        }
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const sports = document.querySelectorAll('input[name="sports[]"]:checked');
    if (sports.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار رياضة واحدة على الأقل');
    }
});
</script>