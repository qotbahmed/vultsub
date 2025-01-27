<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = Yii::t('backend', 'Website Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="settings-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
