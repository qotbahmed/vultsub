<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Gallery */

$this->title = Yii::t('app', 'Create Gallery');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gallery'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
