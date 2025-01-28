<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Sponsors */

$this->title = Yii::t('backend','Create Sponsors');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Sponsors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsors-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
