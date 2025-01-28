<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sponsors */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="sponsors-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'sponsors-form',
            'data-start_spinner' => 'true',
            'novalidate' => 'novalidate' // Disable default HTML5 validation
        ]
    ]); ?>


    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-location-add"></span>
            </span>
            <h4 class="mb-0">
                <?=  $this->title ?>
            </h4>
        </div>
        <div class="mb-0 d-inline-flex align-items-center gap-2">
            <?=   Html::submitButton($model->isNewRecord ? '<span class="isax isax-add"></span> ' . Yii::t('backend', 'Create') : '<span class="isax isax-edit"></span> ' .  Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        </div>
    </div>



    <div class="card">
        <div class="card-body">
        <div class="row">
    <?= $form->errorSummary($model); ?>

 <div class="col-md-4">   <?= $form->field($model, 'id')->textInput(['placeholder' => Yii::t('app', 'Id')]) ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')]) ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'path')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Path')]) ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'base_url')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Base Url')]) ?> </div>




        </div>

        </div>

        <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
         </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>