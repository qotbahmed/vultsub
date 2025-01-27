<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \common\helpers\multiLang\MyMultiLanguageActiveField;


/* @var $this yii\web\View */
/* @var $model backend\models\Government */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Customer', 
        'relID' => 'customer', 
        'value' => \yii\helpers\Json::encode($model->customers),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="government-form">
    <?php
        $this->beginContent('@backend/views/public/multi-lang.php');
        $this->endContent();
    ?>

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'government-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

    <?= $form->errorSummary($model); ?>

 <div class="col-md-4">   <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?> </div>

<!-- <div class="col-md-4">   --><?//= $form->field($model, 'country_code')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Country Code')]) ?><!-- </div>-->

<!-- <div class="col-md-4">   --><?//= $form->field($model, 'government_code')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Government Code')]) ?><!-- </div>-->

 <div class="col-md-4">
     <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])
         ->widget(MyMultiLanguageActiveField::className());
 ?> </div>



        </div>

        <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>