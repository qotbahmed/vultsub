<?php

use common\helpers\multiLang\MyMultiLanguageActiveField;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="category-form">
    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-location-add"></span>
            </span>
            <h4 class="mb-0">
                إضافة فئه
            </h4>
        </div>
       
    </div>
    <?php
//    $this->beginContent('@backend/views/public/multi-lang.php');
//    $this->endContent();
    ?>
    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'category-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">
            <div class="row">

                <?= $form->errorSummary($model); ?>

                <div class="col-md-12">   <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?> </div>

                <div class="col-md-8">   <?= $form->field($model, 'name')->textInput(['maxlength' => true,
                        'placeholder' => Yii::t('backend', 'Name')]) //->widget(MyMultiLanguageActiveField::className()) ?> </div>


            </div>
        </div>


        <div class="card-footer">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>