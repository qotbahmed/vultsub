<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PointsLogs */

$this->title = 'Create Points Logs';
$this->params['breadcrumbs'][] = ['label' => 'Points Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="points-logs-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
