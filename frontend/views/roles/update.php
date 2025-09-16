<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */


$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend',  'الصلاحيات'), 'url' => ['index']];
if($childView){
    $this->title = 'Update permission';
    $this->params['breadcrumbs'][] = ['label' => $model->itemParent, 'url' => ['view','id'=>$model->itemParent]];
}else{
    $this->title =  'تحديث صلاحية ' . $model->description;
}

$this->params['breadcrumbs'][] =  $this->title;
?>
<div class="rbac-auth-item-update">
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
