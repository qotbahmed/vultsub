<?php

use common\helpers\multiLang\MyMultiLanguageActiveField;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="faq-form">
    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-location-add"></span>
            </span>
            <h4 class="mb-0">
                إضافة سؤال
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
            'id' => 'faq-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

<!--            --><?php //= $form->errorSummary($model); ?>
            <div class="row">

                <div class="col-md-">   <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?> </div>

                <div class="col-md-8">   <?= $form->field($model, 'question')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Question')]) // ->widget(MyMultiLanguageActiveField::className()); ?> </div>

                <div class="col-md-12">   <?= $form->field($model, 'answer')->textarea(['rows' => 6]) //->widget(MyMultiLanguageActiveField::className());?> </div>


                <div class="col-md-4">
                    <?= $form->field($model, 'status')->dropDownList(
                        [\common\models\base\Faq::STATUS_ACTIVE => Yii::t('backend','Active'), \common\models\base\Faq::STATUS_NOT_ACTIVE => Yii::t('backend','Inactive')],
                        ['prompt' => Yii::t('backend', 'Status')]
                    ) ?>
                </div>


                <div class="col-md-4">
                    <?=
                    $form->field($model, 'category_id')->dropDownList(
                        ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'),
                        ['prompt' => Yii::t('backend',  'Select Category')]
                    ); ?>
                </div>


            </div>
        </div>


        <div class="card-footer">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>