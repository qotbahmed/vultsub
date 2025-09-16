<?php
$this->title = Yii::t('backend', 'Dashboard');


use backend\models\DateRangeForm;
use common\models\User;
use common\widgets\InfoBoxWidget;
use common\widgets\CustomerRequestInfoBoxWidget;
use common\widgets\TechnicalSupportInfoBoxWidget;
use common\widgets\WithdrawalInfoBoxWidget;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\widgets\DatePicker;

$model = new DateRangeForm();

echo newerton\fancybox3\FancyBox::widget([

    'config' => [
        'iframe' => [

            'preload' => false,
            'css' => [
                'width' => '900px',
                'height' => '500px'
            ]
        ],

    ],
]);

?>


<script src="dist/js/pages/dashboard3.js"></script>
<style >
    .datepicker-dropdown{
        right: 30% !important;
        left: auto !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="info-box">
            <div class="info-box-content">
                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <div class="col-lg-3 text<?=Yii::t('backend','-right')?>">
                        <?= $form->field($model, 'from')->widget(DatePicker::classname(), [
                            'language' => 'en',
                            'options' => ['class' => 'form-control', 'id' => 'from'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd', // Set the date format here
                            ],
                        ]) ?>
                    </div>

                    <div class="col-lg-3 text<?=Yii::t('backend','-right')?>">
                        <?= $form->field($model, 'to')->widget(DatePicker::classname(), [
                            'language' => 'en',
                            'options' => ['class' => 'form-control', 'id' => 'to'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd', // Set the date format here
                            ],
                        ]) ?>
                    </div>
                    <br>
                    <div class="col-lg- pr-1">
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <?= Html::submitButton(Yii::t('backend','Search'), ['class' => 'btn btn-primary', 'name' => 'search-button']) ?>
                            </div>
                            <div class="col-lg-6">
                                <?= Html::button(Yii::t('backend', 'Clear'), ['class' => 'btn btn-primary', 'onclick' => 'clearForm()']) ?>
                            </div>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>


                </div>
            </div>
        </div>
    </div>
    </div>
<script>
    function clearForm() {
        document.getElementById("from").value = "";
        document.getElementById("to").value = "";
        window.location.href = '';

    }

</script>

    <div class="row">

        <?=

        InfoBoxWidget::widget([
            'icon' => 'fa-user',
            'color' => 'bg-primary',
            'type' => User::USER_TYPE_PLAYER,
            'from' => $model->from,
            'to' => $model->to
        ])
       ?>
    </div>
