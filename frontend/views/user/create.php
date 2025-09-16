<?php
/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
$this->title = Yii::t('backend', 'Create New');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'profile' => $profile,
    ]) ?>

</div>
