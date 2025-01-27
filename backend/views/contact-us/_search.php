<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\ContactUs $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="contact-us-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>
    <?php echo $form->field($model, 'name') ?>
    <?php echo $form->field($model, 'phone') ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'title') ?>
    <?php // echo $form->field($model, 'message') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
