<?php
/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles yii\rbac\Role[] */
$this->title = Yii::t('backend', 'Create Manager');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'profile' => $profile,
        'roles' => $roles
    ]) ?>

</div>
