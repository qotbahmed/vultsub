<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل التجريبي - Vult</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .signup-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .signup-header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .signup-body {
            padding: 40px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        
        .btn-signup {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
            color: white;
        }
        
        .btn-login {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
            color: white;
        }
        
        .sports-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.5rem;
        }
        
        .trial-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="signup-card">
                    <div class="signup-header">
                        <div class="sports-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 class="mb-2">انضم إلى Vult اليوم!</h3>
                        <p class="mb-3">ابدأ تجربتك المجانية لمدة 7 أيام</p>
                        <div class="trial-badge">
                            <i class="fas fa-gift me-2"></i>تجربة مجانية لمدة 7 أيام
                        </div>
                    </div>
                    <div class="signup-body">
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= Yii::$app->session->getFlash('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= Yii::$app->session->getFlash('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php $form = \yii\widgets\ActiveForm::begin([
                            'id' => 'signup-form',
                            'options' => ['class' => 'needs-validation'],
                            'fieldConfig' => [
                                'template' => '<div class="mb-3">{label}{input}{error}</div>',
                                'labelOptions' => ['class' => 'form-label fw-bold'],
                                'inputOptions' => ['class' => 'form-control'],
                                'errorOptions' => ['class' => 'invalid-feedback'],
                            ],
                        ]); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'academy_name')->textInput(['placeholder' => 'اسم الأكاديمية']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'manager_name')->textInput(['placeholder' => 'اسم المسؤول']) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput(['type' => 'email', 'placeholder' => 'البريد الإلكتروني']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone')->textInput(['placeholder' => 'رقم الهاتف']) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'كلمة المرور']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'branches_count')->textInput(['type' => 'number', 'min' => 1, 'placeholder' => 'عدد الفروع']) ?>
                            </div>
                        </div>
                        
                        <?= $form->field($model, 'main_sport')->textInput(['placeholder' => 'الرياضة الرئيسية (مثل: كرة القدم، كرة السلة، إلخ)']) ?>
                        
                        <div class="d-grid gap-2">
                            <?= \yii\helpers\Html::submitButton('ابدأ تجربتك المجانية', ['class' => 'btn btn-signup btn-lg']) ?>
                        </div>
                        
                        <?php \yii\widgets\ActiveForm::end(); ?>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-3">لديك حساب بالفعل؟</p>
                            <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>" class="btn btn-login btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
