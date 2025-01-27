<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\ArrayHelper;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

$this->beginContent('@frontend/views/layouts/base.php')
?>

<?php if (Yii::$app->session->hasFlash('alert')): ?>
    <?php echo \yii\bootstrap4\Alert::widget([
        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
    ]) ?>
<?php endif; ?>

<?php
$academy = Yii::$app->controller->academy;
$academyTitle = $academy ? Html::encode($academy->title) : '';
$logoSrc = $academy ? $academy->getLogo() : '/img/logo-h-green.png';

NavBar::begin([
    'brandLabel' => '<div class="d-flex gap-3 align-items-center"><img style="max-height: 50px;" src="' . $logoSrc . '" alt=""><p class="small"> ' . $academyTitle . ' </p></div>',
    'brandUrl' => Yii::$app->urlManager->createUrl('/'),
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-light bg-light shadow',
    ],
]);




$avatarPath = '/img/avatar.svg'; // Default avatar

if (!Yii::$app->user->isGuest && Yii::$app->user->identity && Yii::$app->user->identity->userProfile) {
    $avatarPath = Yii::$app->user->identity->userProfile->getNewAvatar() ?? $avatarPath;
}
echo Nav::widget([
    'options' => ['class' => ['navbar-nav', 'ml-auto', 'top-right-nav']],
    'encodeLabels' => false,
    'items' => [
        '<li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex gap-1 align-items-center" data-toggle="dropdown" aria-expanded="false">
                    ' . Html::img($avatarPath ?? '/img/avatar.svg', ['class' => ['img-circle', 'bg-white', 'user-image m-0'], 'alt' => 'User image']) . '
                    
                    <span style="max-width:150px" class="text-truncate d-inline-block">' . Yii::$app->user->identity->userProfile->getFullName() . '</span>' . '

                </a>
                
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- Menu Footer-->
                    <li class="user-footer">  
                        
                            ' . Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class' => 'userMenuLink text-danger', 'data-method' => 'post']) . '
                        
                    </li>
                </ul>
            </li>
            ',
        //                [
        //                    // control sidebar button
        //                    'label' => FAS::icon('th-large'),
        //                    'url'  => '#',
        //                    'linkOptions' => [
        //                        'data' => ['widget' => 'control-sidebar', 'slide' => 'true'],
        //                        'role' => 'button'
        //                    ],
        //                    'visible' => Yii::$app->user->can('administrator'),
        //                ],
    ]
]);

NavBar::end();
?>

<div class="py-3">
    <?= $content ?>
</div>

<?php $this->endContent() ?>