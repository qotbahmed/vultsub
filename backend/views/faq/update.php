<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' =>Yii::t('backend','Faqs') ,
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="faq-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
