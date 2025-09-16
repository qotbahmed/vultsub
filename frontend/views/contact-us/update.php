<?php

/**
 * @var yii\web\View $this
 * @var backend\models\ContactUs $model
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Contact Us',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact uses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="contact-us-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
