<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \common\helpers\multiLang\MyMultiLanguageActiveField;
use trntv\filekit\widget\Upload;
use yii\web\JsExpression;
use kartik\editors\Summernote;

/* @var $this yii\web\View */
/* @var $model backend\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="page-form">

    <?php
    $this->beginContent('@backend/views/public/multi-lang.php');
    $this->endContent();
    ?>


    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'page-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

    <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?php
    echo $form->field($model, 'image')->widget(
        Upload::class,
        [
            'url'=>['image-upload'],
            'acceptFileTypes' => new JsExpression('/(\.|\/)(png|jpeg|jpg)$/i'),
        ])->label('Image (preferred - 1000 px* 600px)  max 1 mega   png images');
    ?>



                <div class="row">
                    <div class="col-md-8">
                        <div class="well">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title'])->widget(MyMultiLanguageActiveField::className()); ?>
                        </div>
                    </div>
                </div>
                
                <div style = "min-height: 500px" class="row">
                    <div class="col-md-12" >
                        <div class="tab-content" style="margin-top: 10px;">
                            <div class="tab-pane en" >
                                <?php
                                    // Usage with ActiveForm and model and default settings
                                    echo $form->field($model, 'body')->widget(Summernote::class, [
                                        'options' => ['placeholder' => 'Description..' , 'style' => 'min-height: 500px',]
                                    ]);
                                ?>
                            </div>
                            <div class="tab-pane ar active" >
                                <?php
                                    // Usage with ActiveForm and model and default settings
                                    echo $form->field($model, 'body_ar')->widget(Summernote::class, [
                                        'options' => ['placeholder' => 'Add product definition..' , 'style' => 'min-height: 500px',]
                                    ]);
                                ?>
                            </div>
                        </div>
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