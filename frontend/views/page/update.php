<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Page */

$this->title = Yii::t('backend', 'Update Content:') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Page'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
