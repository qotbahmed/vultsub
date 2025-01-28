<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\PointsLogs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Points Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="points-logs-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Points Logs'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
                        
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        [
            'attribute' => 'user.id',
            'label' => 'User',
        ],
        'user_name',
        'user_mobile',
        'points_num',
        'type',
        'page_num',
        'time:datetime',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>