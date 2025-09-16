<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\rbac\models\RbacAuthItem;
use backend\modules\rbac\models\RbacAuthItemChild;

/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */
/* @var $form yii\widgets\ActiveForm */
$allRoles = RbacAuthItem::find()->where(['type'=>1])
        ->andWhere(['or',['assignment_category'=> RbacAuthItem::CONTROLLER_ASSIGN],
        ['assignment_category'=> RbacAuthItem::CATEGORY_ASSIGN],
        ['assignment_category'=> RbacAuthItem::MODULE_ASSIGN]])->select(['name','description'])->all();
?>

<div class="rbac-auth-item-form">

    <?php $form = ActiveForm::begin() ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?php if(!$childView) : ?>
                <?= $form->field($model, 'type')->dropDownList([1 => 'صلاحية اساسية', 2 => 'صلاحية فرعية']) ?>
            <?php endif; ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6])->label('الوصف') ?>

        </div>
        <div class="col-md-6">
            <?php if(!$childView) : ?>
                <?=
                    $form->field($model, 'isParent')->radioList([
                        '1'=> Yii::t('backend','Yes'),
                        '0'=> Yii::t('backend','No')
                    ])->label('صلاحية رئيسية');
                ?>
                <?= $form->field($model, 'itemParent')->widget(\kartik\select2\Select2::classname(), [
                    'data' => ArrayHelper::map($allRoles, 'name', 'description'),
                    'options' => [
                        'placeholder' => 'اختر الصلاحية الرئيسية',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'dir'=>'rtl'
                    ],
                ])->label('الصلاحية الرئيسية');

                ?>
            <?php endif; ?>

            <?= $form->field($model, 'assignment_category')->dropDownList(RbacAuthItem::getAssignCategoriesList(),['prompt' => 'اختر نوع الصلاحية']) ?>
        </div>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>
