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
    input[type=checkbox] {
        height: 20px !important;
        display: inline !important;
        width: 20px !important;
    }

    .payment {
        margin-left: 8px !important
    }

    label:has(input.payment) {
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

            <div class="row">

            </div>
            <div class="row">

                <div class="col-md-6">
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' =>Yii::t('backend', 'Address')])->widget(MyMultiLanguageActiveField::className()); ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Email')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'points_per_second')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Points per second')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'reading_points_delay')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Reading points delay')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'max_daily_points_per_user')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Maximum points collected daily for each person')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'daily_points')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Points for the day')]) ?>
                </div>
            </div>

        </div>


    </div>


    <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>

    </div>
</div>

<?php ActiveForm::end(); ?>

</div>