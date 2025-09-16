<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Government */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Government'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="government-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('backend', 'Government').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'country_code',
        'government_code',
        'title',
        'title_ar',
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
