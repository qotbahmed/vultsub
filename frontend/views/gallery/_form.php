<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use trntv\filekit\widget\Upload;


/* @var $this yii\web\View */
/* @var $model backend\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'GalleryPhotos', 
        'relID' => 'gallery-photos', 
        'value' => \yii\helpers\Json::encode($model->galleryPhotos),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="gallery-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => 'gallery-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

    <?= $form->errorSummary($model); ?>

 <div class="col-md-4">   <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?> </div>

 <div class="col-md-4">   <?= $form->field($model, 'title')->textInput(['maxlength' => true,
         'readonly'=>true,
         'placeholder' => Yii::t('app', 'Title')]) ?> </div>


            <div class="col-md-8">

                <?php echo $form->field($model, 'photos')->widget(
                    Upload::class,
                    [
                        'url' => ['/file/storage/upload'],
                        'sortable' => true,
                        'maxFileSize' => 10000000, // 10 MiB
                        'maxNumberOfFiles' => 10,
                    ]);
                ?>
            </div>

        </div>



        <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>