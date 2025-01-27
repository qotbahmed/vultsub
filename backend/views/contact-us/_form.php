<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\ContactUs $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="contact-us-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php echo $form->errorSummary($model); ?>

                    <div class="col-md-4"><?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div><div class="col-md-4"><?php echo $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div><div class="col-md-4"><?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div><div class="col-md-4"><?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div><div class="col-md-4"><?php echo $form->field($model, 'message')->textarea(['rows' => 6]) ?>
                </div><div class="col-md-4"><?php echo $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>
                </div><div class="col-md-4"><?php echo $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>
                </div>                </div>


            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
