<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;

/* @var $this yii\web\View */
/* @var $model frontend\models\SignupForm */

$this->title = 'Sign Up - Vult SaaS';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-0">Start Your Free Trial</h2>
                        <p class="mb-0">Get 7 days free access to all features</p>
                    </div>
                    <div class="card-body p-5">
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <?= Alert::widget([
                                'body' => Yii::$app->session->getFlash('success'),
                                'options' => ['class' => 'alert-success']
                            ]) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <?= Alert::widget([
                                'body' => Yii::$app->session->getFlash('error'),
                                'options' => ['class' => 'alert-danger']
                            ]) ?>
                        <?php endif; ?>

                        <?php $form = ActiveForm::begin([
                            'id' => 'signup-form',
                            'options' => ['class' => 'needs-validation'],
                            'fieldConfig' => [
                                'template' => '<div class="form-group">{label}{input}{error}</div>',
                                'labelOptions' => ['class' => 'form-label font-weight-bold'],
                                'inputOptions' => ['class' => 'form-control'],
                                'errorOptions' => ['class' => 'invalid-feedback']
                            ]
                        ]); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'first_name')->textInput(['autofocus' => true, 'placeholder' => 'Enter your first name']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Enter your last name']) ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'placeholder' => 'Enter your email address']) ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Create a password']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Confirm your password']) ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'academy_name')->textInput(['placeholder' => 'Enter your academy name']) ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'branches_count')->textInput(['type' => 'number', 'min' => 1, 'max' => 100, 'placeholder' => 'Number of branches']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Phone number (optional)']) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <?= $form->field($model, 'agree_terms')->checkbox([
                                    'template' => '<div class="form-check">{input} {label}</div>{error}',
                                    'labelOptions' => ['class' => 'form-check-label'],
                                    'inputOptions' => ['class' => 'form-check-input']
                                ]) ?>
                                <small class="form-text text-muted">
                                    By signing up, you agree to our <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>.
                                </small>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <?= Html::submitButton('Start Free Trial', ['class' => 'btn btn-primary btn-lg px-5', 'name' => 'signup-button']) ?>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-0">Already have an account? <a href="<?= \yii\helpers\Url::to(['login']) ?>">Sign in</a></p>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

                <!-- Features Preview -->
                <div class="row mt-5">
                    <div class="col-md-4 text-center">
                        <div class="feature-box p-4">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5>Student Management</h5>
                            <p class="text-muted">Manage students, attendance, and progress tracking</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="feature-box p-4">
                            <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                            <h5>Schedule Management</h5>
                            <p class="text-muted">Create and manage class schedules and timetables</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="feature-box p-4">
                            <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                            <h5>Analytics & Reports</h5>
                            <p class="text-muted">Track performance with detailed analytics and reports</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-box {
    transition: transform 0.3s ease;
}
.feature-box:hover {
    transform: translateY(-5px);
}
.card {
    border-radius: 15px;
}
.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
}
</style>
