<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SponsorLog */

$this->title =Yii::t('backend', 'Create Sponsor Log');
$this->params['breadcrumbs'][] = ['label' =>Yii::t('backend',  'Sponsor Log'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsor-log-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
