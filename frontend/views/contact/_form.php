<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \common\helpers\multiLang\MyMultiLanguageActiveField;
use trntv\filekit\widget\Upload;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Contact */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="contact-form">
    <?php
    $this->beginContent('@backend/views/public/multi-lang.php');
    $this->endContent();
    ?>

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'contact-form'
        ]
    ]);
    ?>
    <div class="col-md-4">   <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?> </div>

    <div class="card">
        <div class="card-body">

    <?= $form->errorSummary($model); ?>

            <div class="card">
                <div class="card-header">
                    Contact page Top section
                </div>
                <div class="card-body">
                    <?php
                    echo $form->field($model, 'image')->widget(
                        Upload::class,
                        [
                            'url'=>['image-upload'],
                            'uploadPath' =>"pages",
                            'acceptFileTypes' => new JsExpression('/(\.|\/)(png|jpeg|jpg)$/i'),
                        ])->label('Image (preferred - 600px*500px)')
                    ?>

                    <div class="col-md-12">   <?=  $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])->widget(MyMultiLanguageActiveField::className());?> </div>
                    <div class="col-md-12">   <?= $form->field($model, 'header_one')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Header One')])->widget(MyMultiLanguageActiveField::className());?> </div>
                    <div class="col-md-12">   <?= $form->field($model, 'header_two')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Header Two')])->widget(MyMultiLanguageActiveField::className());?> </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Contact page Middle section
                </div>
                <div class="card-body"><div class="row">

                        <div class="col-md-6">   <?= $form->field($model, 'first_section_header')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'First Section Header')])->widget(MyMultiLanguageActiveField::className()); ?> </div>

                        <div class="col-md-6">   <?= $form->field($model, 'first_section_details')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'First Section Details')])->widget(MyMultiLanguageActiveField::className()); ?> </div>

                        <div class="col-md-6">   <?= $form->field($model, 'second_section_title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Second Section Title')])->widget(MyMultiLanguageActiveField::className()); ?> </div>

                        <div class="col-md-6">   <?= $form->field($model, 'second_section_details')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Second Section Details')])->widget(MyMultiLanguageActiveField::className()); ?> </div>

                        <div class="col-md-6">   <?= $form->field($model, 'third_section_title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Third Section Title')])->widget(MyMultiLanguageActiveField::className()); ?> </div>

                        <div class="col-md-6">   <?= $form->field($model, 'third_section_details')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Third Section Details')])->widget(MyMultiLanguageActiveField::className()); ?> </div>
                    </div>

                </div>
            </div>



        </div>


        <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>