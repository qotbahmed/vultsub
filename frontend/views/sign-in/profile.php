<?php

use common\models\UserProfile;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use rmrevin\yii\fontawesome\FAS;

use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use kartik\widgets\DateTimePicker;
use yii\web\JsExpression;
use common\helpers\multiLang\MyMultiLanguageActiveField;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap4\ActiveForm */

// \mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
//     'viewParams' => [
//         'class' => 'ShopGallery',
//         'relID' => 'shop-gallery',
//         'value' => \yii\helpers\Json::encode($shop->shopGalleries),
//         'isNewRecord' => ($model->isNewRecord) ? 1 : 0
//     ]
// ]);

$this->title = Yii::t('backend', 'Edit profile')
?>

<style >
    .datetimepicker-dropdown-bottom-right{
        right: 40% !important;
        left      : auto !important;
    }
</style>

<?php $form = ActiveForm::begin() ?>
<div class="profile-form">

<?php
    $this->beginContent('@backend/views/public/multi-lang.php');
    $this->endContent();
?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <?php echo $form->field($model, 'picture')->widget(\trntv\filekit\widget\Upload::class, [
                        'url'=>['avatar-upload']
                    ]) ?>
                </div>
                <div class="col-3">
                    <?php echo $form->field($model, 'firstname')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-3">
                    <?php echo $form->field($model, 'middlename')->textInput(['maxlength' => 255])->label(Yii::t('backend', 'Middlename')) ?>
                </div>

                <div class="col-3">
                    <?php echo $form->field($model, 'lastname')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-3">
                    <?php echo $form->field($model, 'gender')->dropDownlist([
                        UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                        UserProfile::GENDER_MALE => Yii::t('backend', 'Male')
                    ]) ?>
                </div>
            </div>
            <hr>
            <h2>
                <?= Yii::t('backend', 'Shop') ;?>
            </h2>
            <div class="row">      
                <div class="col-12">
                    <?php echo $form->field($shop, 'image')->widget(\trntv\filekit\widget\Upload::class, [
                        'url'=>['shop-upload']
                    ])->label(Yii::t('backend', 'Shop Image')) ?>
                </div>    
                

                <div class="col-md-12">
                    <?php 
                        echo $form->field($shop, 'gallery')->widget(\trntv\filekit\widget\Upload::classname(), 
                                    ['url'=>['gallery-upload'],                                    
                                    'sortable' => true,
                                    'maxNumberOfFiles'=>10
                        ])->label(Yii::t('frontend', 'Gallery'));

                    // echo $this->render('_formShopGallery', ['row' => \yii\helpers\ArrayHelper::toArray($shop->shopGalleries)]);
                    ?>
                </div>                                

                <!-- <div class="col-md-12">
                    <?php 
                        // echo $form->field($shop, 'cr_document')->widget(FileInput::classname(), [                                                                                        
                        //     'pluginOptions' => [
                        //         'allowedFileExtensions' => ['pdf','jpg','png','csv','docx','doc'],
                        //         'showPreview'           => false,
                        //         'showUpload'            => false,
                        //         'uploadAsync'           => false,
                        //         'showCancel'            => false,                                                                                        
                        //     ]
                        // ])->label(Yii::t('backend', 'CR Document'));
                    ?>
                </div> -->
            

                <div class="col-6">
<?= $form->field($shop, 'title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Shop Title')]) ->widget(MyMultiLanguageActiveField::className())->label(Yii::t('backend', 'Shop Title')); ?> 
                </div>
                <div class="col-6">
                    <?php echo $form->field($shop, 'mobile')->textInput(['maxlength' => 255])->label(Yii::t('backend', 'Phone')) ?>
                </div>                
            </div>
            <div class="row"> 
                <div class="col-md-6">
                    <?= $form->field($shop, 'city')->dropDownList(
                            ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                            ['prompt' => Yii::t('backend',  'Select City')], 
                            ['id' => 'shop-city'])->label(Yii::t('backend', 'City')); 
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($shop, 'district')->widget(DepDrop::classname(), [
                            'options'=>['id'=>'district-id'],
                            'data'=>[$shop->district => \common\models\District::findOne($shop->district)->name ],
                            'pluginOptions'=>[
                                'depends'=>['shop-city'],
                                'placeholder'=> Yii::t('backend', 'Select'),
                                'url'=>Url::to(['/sign-in/districts'])
                            ]
                        ])->label(Yii::t('backend', 'District'));
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $form->field($shop, 'gender')->dropDownlist($shop->gender()) ?>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-md-6">
                    <?= $form->field($shop, 'open_at')->widget(DateTimePicker::class, [
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'HH:ii',
                            'startView' => 'hour', 
                            'minView' => 'hour',   
                            'minuteStep' => 5,    
                        ],
                    ])->label(Yii::t('backend', 'Open At')) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($shop, 'close_at')->widget(DateTimePicker::class, [
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'HH:ii',
                            'startView' => 'hour', 
                            'minView' => 'hour',   
                            'minuteStep' => 5,    
                        ],
                    ])->label(Yii::t('backend', 'Close At')) ?>
                </div>
            </div> -->

            <div class="row">
            <div class="col-12">                        
<?= $form->field($shop, 'about')->textArea(['rows' => 8, 'placeholder' => Yii::t('backend', 'Package name')]) ->widget(MyMultiLanguageActiveField::className())->label(Yii::t('backend', 'Bio')); ?>                         
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-12">
<?= $form->field($shop, 'cancel_terms')->textArea(['rows' => 8, 'placeholder' => Yii::t('backend', 'Package name')]) ->widget(MyMultiLanguageActiveField::className())->label(Yii::t('backend', 'Cancel Terms')); ?> 
                        </div>
                        </div>

            <div class="row">
                <div class="col-12">
<?= $form->field($shop, 'address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('backend', 'Address')]) ->widget(MyMultiLanguageActiveField::className())->label(Yii::t('backend', 'Address')); ?> 
<?php echo $form->field($shop, 'address_text')->textInput(['maxlength' => 255,'style' => 'display:none'])->label(false) ?>

                </div>
            </div>

            


            <div class="row">            
                <div class="col-6">
                    <?php echo $form->field($shop, 'lat')->textInput(['maxlength' => 255,'style' => 'display:none'])->label(false)?>
                </div>
                <div class="col-6">
                    <?php echo $form->field($shop, 'lng')->textInput(['maxlength' => 255,'style' => 'display:none'])->label(false) ?>
                </div>
            </div>


            
            <div class="row">
                <div class="col-md-12">
                    <?php
                        echo \pigolab\locationpicker\LocationPickerWidget::widget([
                        'key' => 'AIzaSyA557TV201eLUIup6QuJZUkE2gl0a5X6EQ',	// require , Put your google map api key
                        'options' => [
                                'style' => 'width: 100%; height: 400px', // map canvas width and height
                            ] ,
                            'clientOptions' => [
                                'location' => [
                                    'latitude'  => 24.774265,
                                    'longitude' => 46.738586,
                                ],
                                'radius'    => 300,
                                'addressFormat' => 'street_number',
                                'inputBinding' => [
                                    'latitudeInput'     => new JsExpression("$('#shop-lat')"),
                                    'longitudeInput'    => new JsExpression("$('#shop-lng')"),                                
                                    'locationNameInput' => new JsExpression("$('#shop-address_text')")
                                ]
                            ]        
                        ]);
                    ?>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <?php echo Html::submitButton(FAS::icon('save').' '.Yii::t('backend', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>