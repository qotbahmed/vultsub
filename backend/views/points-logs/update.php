<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PointsLogs */

$this->title = 'Update Points Logs: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Points Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="points-logs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
