<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Gallery */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Gallery',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gallery'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="gallery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
