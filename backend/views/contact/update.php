<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Contact */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Contact',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="contact-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
