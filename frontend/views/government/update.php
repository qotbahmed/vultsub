<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Government */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Government',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Government'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="government-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
