<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */


$this->params['breadcrumbs'][] = ['label' => 'الصلاحيات', 'url' => ['index']];
if($childView){
    $this->title = 'Create permission';
    $this->params['breadcrumbs'][] = ['label' => $model->itemParent, 'url' => ['view','id'=>$model->itemParent]];
}else{
    $this->title = 'إضافة صلاحيات جديدة';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-auth-item-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'childView' => $childView
    ]) ?>

</div>
