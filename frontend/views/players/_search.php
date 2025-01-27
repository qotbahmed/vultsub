<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\UserSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'd-flex align-items-end justify-content-between flex-wrap gap-3'
        ]
    ]); ?>

    <div class="flex-fill">
        <div class="d-flex gap-2 flex-wrap">
            <?php echo $form->field($model, 'username') ?>

            <!--    --><?php //echo $form->field($model, 'auth_key') 
                        ?>

            <?php echo $form->field($model, 'email') ?>
            <!---->
            <!--    --><?php //echo $form->field($model, 'role') 
                        ?>
            <!---->
            <!--    --><?php //echo $form->field($model, 'status') 
                        ?>
            <!---->
            <!--    --><?php //echo $form->field($model, 'created_at') 
                        ?>
            <!---->
            <!--    --><?php //echo $form->field($model, 'updated_at') 
                        ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(
            '<span class="isax mr-2 isax-search-normal-1"></span><span>' . Yii::t('common', 'Search') . '</span>',
            ['class' => 'btn btn-info rounded-pill']
        ) ?>

        <?= $hasFilters ? Html::resetButton(
            '<span class="isax isax-close-circle mr-2"></span><span>' . Yii::t('common', 'Reset') . '</span>',
            [
                'class' => 'btn btn-link text-body rounded-pill',
                'onclick' => 'window.location.href = window.location.pathname;'
            ]
        ) : null ?>
    </div>

    <?php ActiveForm::end() ?>

</div>