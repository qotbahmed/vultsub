<?php
/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;
use common\assets\SweetAlertAsset;

SweetAlertAsset::register($this);
$bundle = \common\assets\AppArassets::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>" dir="<?php if(Yii::$app->language == 'ar'){ echo 'rtl'; }?>">
<head>


    <meta charset="<?php echo Yii::$app->charset ?>"/>


    <?php

    // Include the meta tags file
    include(__DIR__ . '/../../../common/base/MetaTags.php');
    ?>
    <?php $this->head() ?>
    <?php echo Html::csrfMetaTags() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
    <?php echo $content ?>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
