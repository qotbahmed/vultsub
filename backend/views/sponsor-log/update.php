<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SponsorLog */

$this->title = 'Update Sponsor Log: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sponsor Log', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sponsor-log-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
