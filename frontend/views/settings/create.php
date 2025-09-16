<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = Yii::t('backend', 'Create Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
