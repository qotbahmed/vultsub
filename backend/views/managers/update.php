<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('backend', 'Update') . ' : ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->email]];
$this->params['breadcrumbs'][] = ['label'=>Yii::t('backend', 'Update')];
?>
<div class="user-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'profile' => $profile,
        'roles' => $roles
    ]) ?>

</div>
