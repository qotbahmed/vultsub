<?php
\common\assets\LoginAsset::register($this);

use frontend\models\ParentSignup;
use frontend\modules\user\models\SignupForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


use kartik\password\PasswordInput;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model ParentSignup */
//
//$this->title = Yii::t('backend', 'Sign up');
//$this->params['breadcrumbs'][] = $this->title;
//$this->params['body-class'] = 'login-page';
$encryptedAcademyId = Yii::$app->request->get('slug');
if ($encryptedAcademyId) {
    try {
        if (base64_decode($encryptedAcademyId, true) !== false) {
            $decodedAcademyId = \common\helpers\QrHelper::decrypt($encryptedAcademyId);
        } else {
            throw new \Exception('Invalid base64 string');
        }
    } catch (\Exception $e) {
        Yii::error("Decryption failed: " . $e->getMessage(), __METHOD__);
        $decodedAcademyId = null;
    }
}

?>

<style>
    .btn-file{        
        padding: 15px 30px !important;      
    }
</style>
<div style="background-image: url('/img/banner.jpg');" class="d-flex justify-content-center position-relative align-items-center min-vh-100 h-100 w-100">


        <div class="container mt-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-12">
                    <div class="form-block mx-auto signup-form">
                        <div class="text-center mb-5">
                            <h3> <?= Yii::t('backend', 'Sign up') ;?> <strong class="project-name"><?= Yii::t('common', 'Mawhoob') ?></strong></h3>
                        </div>

                        <?php $form = ActiveForm::begin(['id' => 'signup-form', 'options'=>['enctype'=>'multipart/form-data']]); ?>
                        <?php echo $form->errorSummary($model,['class'=>'alertLogin']) ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $form->field($model, 'firstname', [
                                    'inputTemplate' => '<div class="form-group last mb-3">{input}</div>',
                                ])->label(Yii::t('backend', 'Full Name')) ?>
                            </div>

                            <div class="col-md-6">
                                <?php echo $form->field($model, 'mobile', [
                                    'inputTemplate' => '<div class="form-group last mb-3">{input}</div>',
                                ])->label(Yii::t('backend', 'Phone')) ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo $form->field($model, 'password')->widget(PasswordInput::classname(), [
                                    'language' => 'ar',
                                    'pluginOptions' => [
                                        'showMeter' => true,
                                        'toggleMask' => false
                                    ]
                                ])->label(Yii::t('backend', 'Password'));
                                ?>
                            </div>
                            <div class="col-md-6">
<!--                                --><?php //= $form->field($model, 'academy_id')->widget(Select2::classname(), [
//                                    'data' => ArrayHelper::map(
//                                        \common\models\Academies::find()
//                                            ->where(['main' => 0])
//                                            ->asArray()
//                                            ->all(),
//                                        'id',
//                                        'title'
//                                    ),
//                                    'options' => [
//
//                                        'placeholder' => Yii::t('common','Select academy'),
//                                        'value' => $academyIdFromUrl, // Set the default value from the URL
//
//                                    ],
//                                    'pluginOptions' => ['allowClear' => true],
//                                ]); ?><!--  -->

                                <?= $form->field($model, 'academy_id')->hiddenInput([
                                    'value' => isset($decodedAcademyId) ? $decodedAcademyId : null,
                                ])->label(false); ?>

                            </div>


<!--                            <div class="col-md-6">-->
<!--                                --><?php //echo $form->field($model, 'password_confirm', [
//                                    'inputTemplate' => '<div class="form-group last mb-3">{input}</div>',
//                                ])->passwordInput()->label(Yii::t('backend', 'Confirm Password')) ?>
<!--                            </div>-->
                        </div>
                        <input type="submit" value="<?= Yii::t('backend', 'Sign up') ;?>" class="btn btn-block btn-primary">
                        <?php ActiveForm::end() ?>

                        <div class="text-center mt-5">
                            <?= Yii::t('frontend', 'Have an account?') ;?>
                            <a href="/sign-in/login"><strong class="project-name"><?= Yii::t('frontend', 'Login') ;?></strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>