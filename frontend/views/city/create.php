<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\City */

$this->title = Yii::t('backend', 'Add City for: ').$_SESSION['governmentTitle'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'City'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
