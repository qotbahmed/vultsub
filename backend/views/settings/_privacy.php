<?php

use common\models\UserProfile;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use rmrevin\yii\fontawesome\FAS;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap4\ActiveForm */

$this->title = Yii::t('backend', 'Edit profile')
?>

<?php $form = ActiveForm::begin() ?>
    <div class="profile-form">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?php echo $form->field($model, 'picture')->widget(\trntv\filekit\widget\Upload::class, [
                            'url'=>['avatar-upload']
                        ]) ?>
                    </div>
                    <div class="col-6"><?php echo $form->field($model->user, 'email')->input('email') ?></div>

                    <div class="col-4">
                        <?php echo $form->field($model, 'firstname')->textInput(['maxlength' => 255]) ?>
                    </div>

                    <div class="col-4">
                        <?php echo $form->field($model, 'gender')->dropDownlist([
                            UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                            UserProfile::GENDER_MALE => Yii::t('backend', 'Male')
                        ]) ?>
                    </div>
                </div>


<br>
<hr>
<br>

                <!-- Contact Us Card -->
                <div class="card">
                    <div class="card-header">
                        <h5><?= Yii::t('backend', 'Contact Us') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <?php
                                // WhatsApp Number field (you must add the property to UserProfile)
                                echo $form->field($model, 'whatsapp_number')->textInput([
                                    'maxlength' => 20,
                                    'placeholder' => Yii::t('backend', 'e.g., +966512345678')
                                ])
                                ?>
                            </div>
                            <div class="col-6">
                                <?php
                                // WhatsApp Number field (you must add the property to UserProfile)
                                echo $form->field($model, 'email_contact')->textInput([
                                    'maxlength' => 20,
                                    'placeholder' => Yii::t('backend', 'e.g., email@mail.com')
                                ])
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php //echo $form->field($model, 'middlename')->textInput(['maxlength' => 255]) ?>



                <?php //echo $form->field($model, 'locale')->dropDownlist(Yii::$app->params['availableLocales']) ?>


            </div>
            <div class="card-footer">
                <?php echo Html::submitButton(FAS::icon('save').' '.Yii::t('backend', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>