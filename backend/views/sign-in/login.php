<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\LoginForm */

$this->title = Yii::t('backend', 'قم بتسجيل الدخول');
$this->params['body-class'] = 'login-page';
?>

<div class="d-flex align-items-center min-vh-100 w-100">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center vh-100">

            <!-- Right Side: Login Form -->
            <div class="col-md-4 col-sm-12 vh-100">
                <div class="p-5 d-flex flex-column  h-100">
                    <div class="mb-4">
                        <img src="/img/logo-h-gold.png" style="max-height: 60px;" alt="Logo">
                    </div>
                    <div class="my-auto">
                        <p>مرحباً بك!</p>
                        <h4 class="text-primary mb-5">
                            <?= Yii::t('backend', 'قم بتسجيل الدخول') ?>
                        </h4>

                        <!-- Flash Messages -->
                        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
                            <div class="alert alert-<?= $key ?> alert-dismissible fade show" role="alert">
                                <?= $message ?>
                            </div>
                        <?php endforeach; ?>

                        <!-- Login Form -->
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                            <div class="form-group">
                                <?= $form->field($model, 'username', [
                                    'inputOptions' => ['class' => 'form-control rounded-lg', 'placeholder' => Yii::t('backend', 'أدخل البريد الإلكتروني')],
                                ])->label(Yii::t('backend', 'البريد الإلكتروني'), ['class' => 'font-weight-bold']) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model, 'password', [
                                    'inputOptions' => ['class' => 'form-control rounded-lg', 'placeholder' => Yii::t('backend', 'أدخل كلمة المرور')],
                                ])->passwordInput()->label(Yii::t('backend', 'كلمة المرور'), ['class' => 'font-weight-bold']) ?>
                            </div>

                            <div class="form-group form-check d-flex justify-content-between align-items-center">
                                <label class="form-check-label">
                                    <?= Html::activeCheckbox($model, 'rememberMe', ['class' => 'form-check-input']) ?>
                                    <?= Yii::t('backend', 'احفظ البريد الإلكتروني و كلمة المرور للمرة القادمة') ?>
                                </label>
                                <a href="#" class="text-primary small"> <?= Yii::t('backend', 'نسيت كلمة المرور؟') ?> </a>
                            </div>

                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('backend', 'تسجيل الدخول'), ['class' => 'btn btn-primary btn-block rounded-lg']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

            <!-- Left Side: Illustration -->
            <div class="col-md-8 d-none d-md-flex align-items-center justify-content-center">
                <img src="/img/undraw_two_fact.png" alt="Login Illustration">
            </div>

        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Tajawal', sans-serif; /* Arabic-friendly font */
        background: #f8f9fa;
    }
    .card {
        background: #ffffff;
        border-radius: 12px;
    }
    .btn-primary {
        background: #4c82f7;
        border: none;
        padding: 12px;
        font-size: 16px;
    }
    .form-control {
        height: 50px;
        font-size: 16px;
        border-radius: 8px;
    }
</style>
