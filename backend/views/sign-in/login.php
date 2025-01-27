<?php
\common\assets\LoginAsset::register($this);

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\LoginForm */

$this->title = Yii::t('backend', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login-page';
?>

<div style="background-image: url('/img/banner.jpg');" class="d-flex justify-content-center position-relative align-items-center min-vh-100 h-100 w-100">
    <div class="overlay"></div>
 
    <div style="max-width: 500px;" class="col-12 col-md-6 col-lg-5 mx-auto">
        <div class="form-block ">
            <div class="text-center mb-5">
                <img src="/img/logo-h-green.png" style="max-height: 80px;" alt="">
            </div>
           <!-- Flash Message Section -->
<?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
    <div class="alert alert-<?= $key ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
    </div>
<?php endforeach; ?>
<!-- End Flash Message Section -->

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?php echo $form->errorSummary($model, ['class' => 'alertLogin']) ?>
            <?php echo $form->field($model, 'username', [
                'inputTemplate' => '<div class="form-group first mb-2">{input}</div>',
            ]) ?>
            <?php echo $form->field($model, 'password', [
                'inputTemplate' => '<div class="form-group last mb-3">{input}</div>',
            ])->passwordInput() ?>
            <div class="d-sm-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-3 mb-sm-0"><span class="caption">
                        <?= yii::t('common', 'Remember Me') ?>
                    </span>
                    <input name="LoginForm[rememberMe]" value="1" type="checkbox" checked="checked" />
                    <div class="control__indicator"></div>
                </label>
            </div>
            
            <input type="submit" value="<?= yii::t('common', 'Sign In') ?>" class="btn btn-block btn-primary">
            <?php ActiveForm::end() ?>
        </div>
    </div>


</div>
