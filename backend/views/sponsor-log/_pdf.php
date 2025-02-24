<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\SponsorLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sponsor Log', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-log-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Sponsor Log'.' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        [
                'attribute' => 'sponsors.title',
                'label' => 'Sponsor'
        ],
        'amount',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
