<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GalleryPhotos */

$this->title = Yii::t('backend', 'Create new slide');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Home Page Slider'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-photos-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
