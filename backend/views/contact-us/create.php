<?php

/**
 * @var yii\web\View $this
 * @var backend\models\ContactUs $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Contact Us',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact uses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-us-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
