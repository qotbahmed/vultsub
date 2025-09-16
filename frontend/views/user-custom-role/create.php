<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */


$this->params['breadcrumbs'][] = ['label' => 'صلاحيات المديرين', 'url' => ['index']];
$this->title = 'إضافة صلاحية جديدة';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-auth-item-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'modules'=> $modules,
    ]) ?>

</div>
