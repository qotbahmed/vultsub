<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\GalleryPhotos */

?>
<div class="gallery-photos-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->name) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        [
            'attribute' => 'gallery.title',
            'label' => Yii::t('backend', 'Gallery'),
        ],
        'path',
        'base_url:url',
        'type',
        'size',
        'name',
        'title',
        'header_one',
        'header_two',
        'header_three',
        'has_more',
        'url:url',
        'heder_four',
        'sort',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>