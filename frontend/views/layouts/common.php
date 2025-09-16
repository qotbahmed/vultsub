<?php

use common\assets\SweetAlertAsset;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var string $content
 */

//if (Yii::$app->language == 'ar') {
//    $bundle = \common\assets\AppArassets::register($this);
//    $dir = 'rtl';
//} else {
//    $bundle = \common\assets\AppAssets::register($this);
//    $dir = 'ltr';
//}
SweetAlertAsset::register($this);
$bundle = \common\assets\AppArassets::register($this);
$dir = 'ltr';

$this->params['body-class'] = $this->params['body-class'] ?? null;
$keyStorage = Yii::$app->keyStorage;
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html dir="<?= $dir ?>" lang="<?= Yii::$app->language ?>">

    <head>
        <!-- ?? META TAGS -->
        <?php

        // Include the meta tags file
        include(__DIR__ . '/../../../common/base/MetaTags.php');
        ?>
        <!-- ?? META TAGS -->

        <?php echo Html::csrfMetaTags() ?>
        <title><?php echo Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <style>
            .datepicker-dropdown {
                left: 10% !important;
                right: auto !important;
            }

            .select2-selection__rendered {
                width: 100px !important;
            }

            .fixed-avatar {
                width: 100px;
                height: 100px;
                position: relative;
                overflow: hidden;
            }

            .fixed-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
        </style>
    </head>
    <?php echo Html::beginTag('body', [
        'class' => implode(' ', [
            ArrayHelper::getValue($this->params, 'body-class'),
            // $keyStorage->get('adminlte.sidebar-fixed') ? 'layout-fixed' : null,
            // $keyStorage->get('adminlte.sidebar-mini') ? 'sidebar-mini' : null,
            'sidebar-mini',
            'layout-fixed',
            $keyStorage->get('adminlte.sidebar-collapsed') ? 'sidebar-collapse' : null,
            $keyStorage->get('adminlte.navbar-fixed') ? 'layout-navbar-fixed' : null,
            $keyStorage->get('adminlte.body-small-text') ? 'text-sm' : null,
            $keyStorage->get('adminlte.footer-fixed') ? 'layout-footer-fixed' : null,
        ]),
    ])?>


    <?php $this->beginBody() ?>
    <?php echo $content ?>
    <?php $this->endBody() ?>
    <?php echo Html::endTag('body') ?>

    </html>
<?php $this->endPage() ?>