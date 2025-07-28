<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\models\UserProfile;


/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $permissions yii\rbac\Permission[] */


?>


<div class="schools-form  innerForms">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin() ?>
            <?= $form->errorSummary($model) ?>

            <div class="col-md-12">
                <?php echo $form->field($profile, 'picture')->widget(\trntv\filekit\widget\Upload::class, [
                    'url'=>['avatar-upload']
                ]) ?>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <?php echo $form->field($model, 'email')->textInput() ?>
                </div>

                <div class="col-md-4 col-sm-12">
                    <?php //echo $form->field($model, 'password')->passwordInput() ?>
                    <?php  echo $form->field($model, 'password')->passwordInput(); ?>

                </div>
                <div class="col-md-4 col-sm-12">
                    <?= $form->field($model, 'status')->dropDownList(User::statuses(),['prompt'=>'Select...'])?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <?php echo $form->field($profile, 'firstname')->textInput()->label('name')  ?>
                </div>

                <!--<div class="col-md-4 col-sm-12">
            <?php /*echo $form->field($profile, 'lastname')->textInput()  */?>
        </div>-->
                <div class="col-md-4 col-sm-12">

                    <?php echo $form->field($profile, 'gender')->dropDownlist([
                        UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                        UserProfile::GENDER_MALE => Yii::t('backend', 'Male')
                    ]) ?>
                </div>

            </div>

            <div class="form-group row">
                <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

