<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $profile common\models\UserProfile */

$this->title = Yii::t('common', 'Add new child');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-element-3"></span>
            </span>
            <h4 class="mb-0">
                <?= $this->title ?>
            </h4>
        </div>
        <div class="mb-0 d-inline-flex align-items-center gap-2">

<!--            --><?php //= Html::a(
//                Html::tag('i', '', ['class' => 'isax isax-arrow-right-1']) . ' ' . Yii::t('common', 'Back to profile'),
//                ['index'],
//                ['class' => 'btn btn-light']
//            ) ?>
        </div>
    </div>
    <div class="user-create">

        <?= $this->render('_form', [
            'model' => $model,
            'profile' => $profile,
        ]) ?>

    </div>
</div>