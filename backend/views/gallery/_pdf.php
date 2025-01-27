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
            'heading' => Html::encode(Yii::t('app', 'Gallery Photos').' '. $this->title),
        ],
        'columns' => $gridColumnGalleryPhotos
    ]);
?>
    </div>
</div>
