<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\City */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'City',
]) . ' ' . $model->title .' -  for : '.$_SESSION['governmentTitle'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'City'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="city-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
