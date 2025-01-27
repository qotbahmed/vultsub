<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Gallery */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gallery'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Gallery').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
                        
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'title',
        'sort',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
if($providerGalleryPhotos->totalCount){
    $gridColumnGalleryPhotos = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            [
                'attribute' => 'gallery.title',
                'label' => Yii::t('app', 'Gallery')
        ],
            'path',
            'base_url:url',
            'type',
            'size',
            'name',
            'order',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerGalleryPhotos,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-gallery-photos']],
        'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Gallery Photos').' '. $this->title),
        ],
        'columns' => $gridColumnGalleryPhotos
    ]);
}
?>
    </div>
</div>