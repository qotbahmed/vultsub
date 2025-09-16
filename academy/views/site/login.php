<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Vult</title>
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
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-body {
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
        
        .btn-login {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
            color: white;
        }
        
        .btn-trial {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-trial:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <div class="sports-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="mb-2">مرحباً بك في Vult</h3>
                        <p class="mb-0">منصة إدارة الأكاديميات الرياضية</p>
                    </div>
                    <div class="login-body">
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
                            'id' => 'login-form',
                            'options' => ['class' => 'needs-validation'],
                            'fieldConfig' => [
                                'template' => '<div class="mb-3">{label}{input}{error}</div>',
                                'labelOptions' => ['class' => 'form-label fw-bold'],
                                'inputOptions' => ['class' => 'form-control'],
                                'errorOptions' => ['class' => 'invalid-feedback'],
                            ],
                        ]); ?>
                        
                        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'type' => 'email']) ?>
                        
                        <?= $form->field($model, 'password')->passwordInput() ?>
                        
                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => '<div class="form-check mb-3">{input} {label}</div>',
                            'labelOptions' => ['class' => 'form-check-label'],
                            'inputOptions' => ['class' => 'form-check-input'],
                        ]) ?>
                        
                        <div class="d-grid gap-2">
                            <?= \yii\helpers\Html::submitButton('تسجيل الدخول', ['class' => 'btn btn-login btn-lg']) ?>
                        </div>
                        
                        <?php \yii\widgets\ActiveForm::end(); ?>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-3">ليس لديك حساب؟</p>
                            <a href="<?= \yii\helpers\Url::to(['/site/trial-signup']) ?>" class="btn btn-trial btn-lg">
                                <i class="fas fa-rocket me-2"></i>ابدأ تجربتك المجانية
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
