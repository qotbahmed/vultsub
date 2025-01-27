<?php

use kartik\widgets\DateTimePicker;
use kartik\widgets\TimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \common\helpers\multiLang\MyMultiLanguageActiveField;


/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
/* @var $form yii\widgets\ActiveForm */

?>

<style>
    input[type=checkbox]{
        height: 20px !important;
        display: inline !important;        
        width: 20px !important;
    }
    .payment{
        margin-left: 8px  !important
    }
    label:has(input.payment){
        font-size: 13px !important;
    }
    /* label:has(input.ddd) { 
        position: relative !important;
        font-size: 12px !important;
        z-index: 0 !important;
        background: transparent !important;
        right: 0 !important;
        display: inline !important;
        top: 10px !important;
        font-weight: 700 !important;
        padding: 2px 10px !important;
    } */
    
</style>

<div class="settings-form">

    <!--    --><?php
    //    $this->beginContent('@backend/views/public/multi-lang.php');
    //    $this->endContent();
    //    ?>

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['index']),
        'options' => [
            'id' => 'settings-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
            <div class="row">

                <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address'])->widget(MyMultiLanguageActiveField::className()); ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Email')]) ?>
                <?= $form->field($model, 'linkedin')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Linkedin')]) ?>
            </div>
                <div class="row">

               <?= $form->field($model, 'facebook')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Facebook')]) ?>

               <?= $form->field($model, 'youtube')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Youtube')]) ?>
               <?= $form->field($model, 'instagram')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Instagram')]) ?>
               <?=$form->field($model, 'whatsapp')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Whatsapp')]) ?>
                </div>

            </div>



        </div>


        <div class="card-footer">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>