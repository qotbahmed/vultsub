<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Page
 */

?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]) ?>

<?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'body')->textarea()?>

<?php echo $form->field($model, 'view')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'status')->checkbox() ?>

<div class="form-group">
    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
