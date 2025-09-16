<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Government */

$this->title = Yii::t('backend', 'Create Government');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Government'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="government-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
