<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\\PointsLogsSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="w-100 form-academy-sport-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'options' => [
                    'class' => 'd-flex  align-items-center justify-content-between flex-wrap gap-3', //needs-validation
                    'novalidate' => 'novalidate'
        ],
        'method' => 'get',
    ]); ?>

    <div class="flex-fill">
        <div class="d-flex gap-2 flex-wrap">
    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => 'Choose User'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'points_num')->textInput() ?>

    <?php /* echo $form->field($model, 'type')->textInput() */ ?>

    <?php /* echo $form->field($model, 'page_num')->textInput() */ ?>

    <?php /* echo $form->field($model, 'time')->textInput() */ ?>

        </div>
    </div>
    <div class="form-group">
        <?=  Html::submitButton(
            '<span class="isax mr-2 isax-search-normal-1"></span><span>' . Yii::t('common', 'Search') . '</span>',
            ['class' => 'btn btn-info rounded-pill']
        ) ?>

        <?=   Html::resetButton(
            '<span class="isax isax-close-circle mr-2"></span><span>' . yii::t('common', 'Reset') . '</span>',
            [
                'class' => 'btn btn-link text-body rounded-pill',
                'onclick' => 'window.location.href = window.location.pathname;'
            ]
        ) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
