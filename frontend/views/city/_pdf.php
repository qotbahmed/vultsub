<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\City */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'City'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('backend', 'City').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'government_id',
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
    $gridColumnCustomer = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        'title',
        [
                'attribute' => 'customerType.title',
                'label' => Yii::t('backend', 'Customer Type')
        ],
        'country',
        [
                'attribute' => 'government.title',
                'label' => Yii::t('backend', 'Government')
        ],
        [
                'attribute' => 'city.title',
                'label' => Yii::t('backend', 'City')
        ],
        'address',
        'phone',
        'description:ntext',
        'lat',
        'lng',
        'avatar_path',
        'avatar_base_url:url',
        'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerCustomer,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-customer']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('backend', 'Customer').' '. $this->title),
        ],
        'columns' => $gridColumnCustomer
    ]);
?>
    </div>
</div>
