<?php

use common\models\UserProfile;
use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $profile common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('common', 'Add new child');
if ($model->close === 1)
    $this->registerJs("$(function() {
            parent.$.fancybox.close();
            parent.location.reload();
        });
    ");
?>


<div class="user-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'user-form'
        ]
    ]); ?>
    <div class="">
        <div class="d-flex flex-wrap">

            <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

            <div class="col-md-6"> <?= $form->field($profile, 'firstname')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Fullname')]) ?> </div>


            <div class="col-md-6">
                <?= $form->field($model, 'mobile')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', '')]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($profile, 'nationality')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', '')]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($profile, 'dob')->widget(\kartik\widgets\DatePicker::classname(), [
                    'options' => ['placeholder' => Yii::t('common', 'Choose date')],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($profile, 'address')->textarea(['rows' => 6]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($profile, 'gender')->radioList([
                    UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                    UserProfile::GENDER_MALE => Yii::t('backend', 'Male')
                ]) ?>
            </div>
        </div>


        <div class="card-footer">
            <?= Html::submitButton($model->isNewRecord ? '<span class="isax isax-add"></span> ' . Yii::t('backend', 'Create') : '<span class="isax isax-edit"></span> ' . Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>