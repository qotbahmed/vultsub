<?php

use yii\helpers\Html;
use common\models\User;
use yii\bootstrap\ActiveForm;
use common\models\UserProfile;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
?>

<style>
    .upload-kit .upload-kit-input {
        width: 200px;
        height: 200px !important;
        margin-top: 13px;
        margin-right: 0 !important;
    }
    .form-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .form-title {
        font-size: 1.5em;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
        color: #343a40;
    }
    .form-group label {
        font-weight: bold;
    }
    .form-actions {
        margin-top: 30px;
        text-align: center;
    }
    .row {
        margin-bottom: 15px;
    }
</style>

<div class="form-container">
    <div class="form-title">
        إضافة مدير جديد
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2 text-center">
            <?php
            echo $form->field($profile, 'picture')->widget(\trntv\filekit\widget\Upload::class, [
                'url' => ['avatar-upload'],
                'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            ])->label(false);
            ?>
        </div>
        <div class="col-md-10">
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-4">
                    <?php echo $form->field($model, 'email') ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->field($model, 'password')->passwordInput() ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->field($profile, 'gender')->dropDownList([
                        UserProfile::GENDER_MALE => Yii::t('backend', 'Male'),
                        UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female')
                    ], ['prompt' => Yii::t('backend', 'Select Gender')]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->field($profile, 'firstname') ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->field($profile, 'lastname') ?>
                </div>
            </div>
            <div class="row">
                <hr>
                <?php
                if (Yii::$app->user->can('manager') or Yii::$app->user->can('administrator')) {
                    ?>
                    <div class="col-md-6 col-sm-12">
                        <?php echo $form->field($model, 'roles')->dropDownList(User::ListCustomRoles(), ['prompt' => Yii::t('common', 'Select Role')]); ?>
                    </div>
                <?php } ?>
                <?php if (!$model->getModel()->isNewRecord) {?>
                    <div class="col-md-6 col-sm-12">
                        <?php echo $form->field($model, 'status')->dropDownList(User::statuses(), ['prompt' => Yii::t('common', 'Select Status')]) ?>
                    </div>
                <?php }else{
                    ?>
                    <div class="col-md-6 col-sm-12"></div>
                    <?
                } ?>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
