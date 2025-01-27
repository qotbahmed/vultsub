<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('backend', 'Settings').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'website_title',
        'phone',
        'email:email',
        'notification_email:email',
        'address',
        'facebook',
        'youtube',
        'twitter',
        'instagram',
        'linkedin',
        'whatsapp',
        'app_ios',
        'app_android',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
