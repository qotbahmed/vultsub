<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use trntv\filekit\widget\Upload;
use yii\web\JsExpression;
use \common\helpers\multiLang\MyMultiLanguageActiveField;


/* @var $this yii\web\View */
/* @var $model backend\models\GalleryPhotos */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="gallery-photos-form">
    <?php
    $this->beginContent('@backend/views/public/multi-lang.php');
    $this->endContent();
    ?>

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'gallery-photos-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

    <?= $form->errorSummary($model); ?>

 <div class="col-md-4">   <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?> </div>

<!-- <div class="col-md-4">   --><?//= $form->field($model, 'gallery_id')->widget(\kartik\widgets\Select2::classname(), [
//        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Gallery::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
//        'options' => ['placeholder' => Yii::t('backend', 'Choose Gallery')],
//        'pluginOptions' => [
//            'allowClear' => true
//        ],
//    ]) ?><!-- </div>-->

            <?php
            echo $form->field($model, 'image')->widget(
                Upload::class,
                [
                    'url'=>['image-upload'],
                    'uploadPath' =>"slider",
                    'maxFileSize' => 2 * 1024 * 1024, // 2Mb

                    'acceptFileTypes' => new JsExpression('/(\.|\/)(png|jpeg|jpg)$/i'),
                ])->label('Image (preferred - 1900px*850px) max 2 mega');;
            ?>
<div class="row">
<!--  <div class="col-md-4">   --><?//= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Name')]) ?><!-- </div>-->

 <div class="col-md-4">   <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Title')])
         ->textInput(['maxlength' => true])->widget(MyMultiLanguageActiveField::className());
     ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'header_one')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Header One')])
         ->textInput(['maxlength' => true])->widget(MyMultiLanguageActiveField::className());
 ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'header_two')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Header Two')])
         ->textInput(['maxlength' => true])->widget(MyMultiLanguageActiveField::className());
 ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'header_three')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Header Three')])
         ->textInput(['maxlength' => true])->widget(MyMultiLanguageActiveField::className());
 ?> </div>
 <div class="col-md-4">   <?= $form->field($model, 'heder_four')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Heder Four')])
         ->textInput(['maxlength' => true])->widget(MyMultiLanguageActiveField::className());
 ?> </div>
</div>

  <div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'has_more')->checkbox([
            'label' => Yii::t('app', 'Has more details')
        ]); ?>
    </div>

        <div class="col-md-4"  id="hasMoreItems"  style="display: <?php echo ($model->has_more == 1) ? "block": "none"?>" >
            <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Url')]) ?>
        </div>
  </div>



        </div>


        <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php

$script = <<< JS
$('#galleryphotos-has_more').change(function(){

if($(this).is(':checked')) {
$('#hasMoreItems').show();

}else{

 $('#hasMoreItems').hide();

}

});
JS;
$this->registerJs($script);
?>