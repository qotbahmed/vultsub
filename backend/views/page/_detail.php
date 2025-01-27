<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Page */

?>
<div class="page-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'slug',
        'title',
        'body:ntext',
        'view',
        'image_base_path',
        'image_path',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>