<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GalleryPhotos */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Gallery Photos',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Gallery Photos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="gallery-photos-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
