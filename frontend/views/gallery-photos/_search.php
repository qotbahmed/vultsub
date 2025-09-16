<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\GalleryPhotosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-gallery-photos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'gallery_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Gallery::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('backend', 'Choose Gallery')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'base_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?php /* echo $form->field($model, 'size')->textInput() */ ?>

    <?php /* echo $form->field($model, 'name')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'title')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'header_one')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'header_two')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'header_three')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'has_more')->textInput() */ ?>

    <?php /* echo $form->field($model, 'url')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'heder_four')->textInput(['maxlength' => true]) */ ?>

    <?php /* echo $form->field($model, 'order')->textInput() */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
