<?php
/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles yii\rbac\Role[] */
$this->title = "إضافة مدير للنظام";
$this->params['breadcrumbs'][] = ['label' => 'مديري النظام', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'profile' => $profile,
    ]) ?>

</div>
