<?php
\common\assets\LoginAsset::register($this);
//\frontend\assets\LoginAsset::register($this);

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \academyadmin\models\LoginForm */

$this->title = Yii::t('backend', 'قم بتسجيل الدخول');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login-page';



?>

<div class="d-flex justify-content-center position-relative align-items-center min-vh-100 h-100 w-100">
    <div class="overlay"></div>
    <div style="max-width: 500px;" class="col-12 col-md-6 col-lg-5 mx-auto">

        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
            <div class="alert alert-<?= $key ?> alert-dismissible" role="alert" style="background-color: white">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?= $message ?>
            </div>
        <?php endforeach; ?>


        <div class="form-block ">
            <div class="mb-4">
                <img src="/img/logo-h-gold.png" style="max-height: 60px;" alt="Logo">
            </div>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?php echo $form->errorSummary($model, ['class' => 'alertLogin']) ?>
            <?php echo $form->field($model, 'identity', [
                'inputTemplate' => '<div class="form-group first mb-2">{input}</div>',
            ]) ?>
            <?php echo $form->field($model, 'password', [
                'inputTemplate' => '<div class="form-group last mb-3">{input}</div>',
            ])->passwordInput() ?>
            <div class="d-sm-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-3 mb-sm-0"><span class="caption">
                        <?= yii::t('common', 'تذكرني') ?>
                    </span>
                    <input name="LoginForm[rememberMe]" value="1" type="checkbox" checked="checked" />
                    <div class="control__indicator"></div>
                </label>
            </div>

            <input type="submit" value="<?= yii::t('common', 'تسجيل الدخول') ?>" class="btn btn-block btn-primary">
            <?php ActiveForm::end() ?>

        </div>
    </div>


</div>