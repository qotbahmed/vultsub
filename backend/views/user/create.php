<?php
/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles yii\rbac\Role[] */
$this->title = Yii::t('backend', 'Create New').' '.\common\models\User::UserRoleName( Yii::$app->session->get('UserRole') );

$this->params['breadcrumbs'][] = ['label' => \common\models\User::UserRoleName( Yii::$app->session->get('UserRole') ).Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'profile' => $profile,
        'roles' => $roles,
        'mainAcademiesMap' => $mainAcademiesMap,
    ]) ?>

</div>
