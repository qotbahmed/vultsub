<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="settings-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'points_per_second')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'قيمة النقاط المحصلة لكل صفحة (x نقاط)')]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'reading_points_delay')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'الحد الادني من الوقت للقضاء في الصفحة (x ثواني)')]) ?>
                </div>


                <div class="col-md-6">
                    <?= $form->field($model, 'max_daily_points_per_user')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'الحد الأقصى للنقاط اليومية لكل مستخدم')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'points_earned_per_riyal')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'النقاط المحصلة لكل ريال')]) ?>
                </div>

            </div>
        </div>
    </div>

    <div class="card-footer">
        <?= Html::submitButton(Yii::t('backend', 'حفظ التعديلات'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
