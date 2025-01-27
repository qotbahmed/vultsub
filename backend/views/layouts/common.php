<?php
/**
 * @author Eugine Terentev <eugine@terentev.net>
 * @author Victor Gonzalez <victor@vgr.cl>
 * @var yii\web\View $this
 * @var string $content
 */


use backend\modules\system\models\SystemLog;
use backend\widgets\MainSidebarMenu;
use common\models\TimelineEvent;
use yii\log\Logger;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Html;
use rmrevin\yii\fontawesome\FAS;
use common\components\keyStorage\FormModel;
use common\components\keyStorage\FormWidget;

// $bundle = BackendArAsset::register($this);
Yii::info(Yii::$app->components["i18n"]["translations"]['*']['class'], 'test');

$keyStorage = Yii::$app->keyStorage;

$logEntries = [
    [
        'label' => Yii::t('backend', 'You have {num} log items', ['num' => SystemLog::find()->count()]),
        'linkOptions' => ['class' => ['dropdown-item', 'dropdown-header']]
    ],
    '<div class="dropdown-divider"></div>',
];
foreach (SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry) {
    $logEntries[] = [
        'label' => FAS::icon('exclamation-triangle', ['class' => [$logEntry->level === Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow']]). ' '. $logEntry->category,
        'url' => ['/system/log/view', 'id' => $logEntry->id]
    ];
    $logEntries[] = '<div class="dropdown-divider"></div>';
}

$logEntries[] = [
    'label' => Yii::t('backend', 'View all'),
    'url' => ['/system/log/index'],
    'linkOptions' => ['class' => ['dropdown-item', 'dropdown-footer']]
];
?>

<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
<div class="wrapper">
    <!-- navbar -->
    <?php NavBar::begin([
        'renderInnerContainer' => false,
        'options' => [
            'class' => [
                'main-header',
                'navbar',
                'navbar-expand',
                'navbar-white',
                'border-bottom',
                'sticky-top',
                $keyStorage->get('adminlte.navbar-no-border') ? 'border-bottom-0' : null,
                $keyStorage->get('adminlte.navbar-small-text') ? 'text-sm' : null,
            ],
        ],
    ]); ?>

        <!-- left navbar links -->
        <?php echo Nav::widget([
            'options' => ['class' => ['navbar-nav']],
            'encodeLabels' => false,
            'items' => [
                [
                    // sidebar menu toggler
                    'label' => '<i class="fas fa-bars"></i>',
                    'url' => '#',
                    'options' => [
                        'data' => ['widget' => 'pushmenu'],
                        'role' => 'button',
                    ]
                ],
            ]
        ]); ?>
        <!-- /left navbar links -->

        <!-- right navbar links -->
        <?php echo Nav::widget([
            'options' => ['class' => ['navbar-nav', 'ml-auto', 'top-right-nav']],
            'encodeLabels' => false,
            'items' => [
                [
                    // timeline events
                    'label' => '<img src="/img/svg/bell.svg" alt="bell">'.' <span class="badge badge-success navbar-badge">'.TimelineEvent::find()->today()->count().'</span>',
                    'url'  => ['/timeline-event/index']
                ],
                '<li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        '.Html::img('/img/avatar.svg', ['class' => ['img-circle',  'bg-white', 'user-image'], 'alt' => 'User image']).'
                        '.'
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            '.'
                            <p>
                                '.Yii::$app->user->identity->publicIdentity.'
                                <small>'.Yii::t('backend', 'Member since {0, date, short}', Yii::$app->user->identity->created_at).'</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                          
                                '.Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class' => 'userMenuLink']).'
                            
                            
                                '.Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class' => 'userMenuLink']).'
                            
                            
                                '.Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class' => 'userMenuLink', 'data-method' => 'post']).'
                            
                        </li>
                    </ul>
                </li>
                ',

            ]
        ]); ?>
        <!-- /right navbar links -->

    <?php NavBar::end(); ?>
    <!-- /navbar -->

    <!-- main sidebar -->
    <aside class="main-sidebar sidebar-light-primary  elevation-4 <?php echo $keyStorage->get('adminlte.sidebar-no-expand') ? 'sidebar-no-expand' : null ?>">
        <!-- brand logo -->
        <a href="<?php echo Yii::getAlias('@backendUrl') ?>" class="brand-link  <?php echo $keyStorage->get('adminlte.brand-text-small') ? 'text-sm' : null ?>">
            <img src="/img/logo-h-gold.png" alt="Logo" class="brand-image lg" width="auto">
            <img src="/img/logo-sm-gold.png" alt="Logo" class="brand-image mini" width="auto">
        </a>
        <!-- /brand logo -->

        <!-- sidebar -->
        <div class="sidebar">
            <!-- sidebar user panel -->
            <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?php echo Html::img(
                        Yii::$app->user->identity->userProfile->getAvatar('/img/avatar.svg'),
                        ['class' => ['img-circle', 'bg-white'], 'alt' => 'User image']
                    ) ?>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?php echo Yii::$app->user->identity->publicIdentity ?></a>
                </div>
            </div> -->
            <!-- /sidebar user panel -->

            <!-- sidebar menu -->
            
            <nav>
                <?php echo MainSidebarMenu::widget([
                    'options' => [
                        'class' => [
                            'nav',
                            'nav-pills',
                            'nav-sidebar',
                            'flex-column',
                            $keyStorage->get('adminlte.sidebar-small-text') ? 'text-sm' : null,
                            $keyStorage->get('adminlte.sidebar-flat') ? 'nav-flat' : null,
                            $keyStorage->get('adminlte.sidebar-legacy') ? 'nav-legacy' : null,
                            $keyStorage->get('adminlte.sidebar-compact') ? 'nav-compact' : null,
                            $keyStorage->get('adminlte.sidebar-child-indent') ? 'nav-child-indent' : null,
                        ],
                        'data' => [
                            'widget' => 'treeview',
                            'accordion' => 'false'
                        ],
                        'role' => 'menu',
                    ],
                    'items' => require __DIR__ . '/menu/Menu.php' ,
                ]) ?>
            </nav>
            <!-- /sidebar menu -->
        </div>
        <!-- /sidebar -->
    </aside>
    <!-- /main sidebar -->

    <!-- content wrapper -->
    <div class="content-wrapper" style="min-height: 402px;">
        <!-- content header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="title text-dark"><?php echo Html::encode($this->title) ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <?php echo Breadcrumbs::widget([
                            'tag' => 'ol',
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            'options' => ['class' => ['breadcrumb']]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /content header -->

        <!-- main content -->
        <section class="content">
            <div class="container-fluid">
<!--                --><?php //if (Yii::$app->session->hasFlash('alert')) : ?>
<!--                    --><?php //echo Alert::widget([
//                        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
//                        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
//                    ]) ?>
<!--                --><?php //endif; ?>


                <?php
                if(Yii::$app->session->hasFlash('alert')){
                    echo \kartik\growl\Growl::widget([
                        'type' => \yii\helpers\ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'type'), //Growl::TYPE_SUCCESS,// ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'type'), //
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        //'title' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'title'),
                        'showSeparator' => true,
                        'body' => \yii\helpers\ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'showSeparator' => false,
                        'pluginOptions' => [
                            'showProgressbar' => true,
                            'placement' => [
                                'from' => 'bottom',
                                'align' => 'center',
                            ]
                        ]
                    ]);
                }

                ?>



                <?php echo $content ?>
            </div>
        </section>
        <!-- /main content -->

        <?php echo Html::a(FAS::icon('chevron-up'), '#', [
            'class' => ['btn', 'btn-primary', 'back-to-top'],
            'role' => 'button',
            'aria-label' => 'Scroll to top',
        ]) ?>
    </div>
    <!-- /content wrapper -->

    <!-- footer -->
    <footer class="main-footer <?php echo $keyStorage->get('adminlte.footer-small-text') ? 'text-sm' : null ?>">
        <strong>&copy; pos <?php echo date('Y') ?></strong>
    </footer>
    <!-- /footer -->

    <?php if (Yii::$app->user->can('administrator')) : ?>
    <!-- control sidebar -->
    <div class="control-sidebar control-sidebar-dark overflow-auto">
        <div class="control-sidebar-content p-3">
            <?php echo FormWidget::widget([
                'model' => new FormModel([
                    'keys' => [
                        'frontend.options' => [
                            'type' => FormModel::TYPE_HEADER,
                            'content' => 'Frontend Options'
                        ],
                        'frontend.maintenance' => [
                            'label' => Yii::t('backend', 'Maintenance mode'),
                            'type' => FormModel::TYPE_DROPDOWN,
                            'items' => [
                                'disabled' => Yii::t('backend', 'Disabled'),
                                'enabled' => Yii::t('backend', 'Enabled'),
                            ],
                        ],
                        'backend.options' => [
                            'type' => FormModel::TYPE_HEADER,
                            'content' => 'Backend Options'
                        ],
                        'adminlte.body-small-text' => [
                            'label' => Yii::t('backend', 'Body small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.no-navbar-border' => [
                            'label' => Yii::t('backend', 'No navbar border'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.navbar-small-text' => [
                            'label' => Yii::t('backend', 'Navbar small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.navbar-fixed' => [
                            'label' => Yii::t('backend', 'Fixed navbar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.footer-small-text' => [
                            'label' => Yii::t('backend', 'Footer small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.footer-fixed' => [
                            'label' => Yii::t('backend', 'Fixed footer'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-small-text' => [
                            'label' => Yii::t('backend', 'Sidebar small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-flat' => [
                            'label' => Yii::t('backend', 'Sidebar flat style'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-legacy' => [
                            'label' => Yii::t('backend', 'Sidebar legacy style'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-compact' => [
                            'label' => Yii::t('backend', 'Sidebar compact style'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-fixed' => [
                            'label' => Yii::t('backend', 'Fixed sidebar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-collapsed' => [
                            'label' => Yii::t('backend', 'Collapsed sidebar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-mini' => [
                            'label' => Yii::t('backend', 'Mini sidebar'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-child-indent' => [
                            'label' => Yii::t('backend', 'Indent sidebar child menu items'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.sidebar-no-expand' => [
                            'label' => Yii::t('backend', 'Disable sidebar hover/focus auto expand'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                        'adminlte.brand-small-text' => [
                            'label' => Yii::t('backend', 'Brand small text'),
                            'type' => FormModel::TYPE_CHECKBOX,
                        ],
                    ],
                ]),
                'submitText' => FAS::icon('save').' '.Yii::t('backend', 'Save'),
                'submitOptions' => ['class' => 'btn btn-primary'],
                'formOptions' => [
                    'action' => ['/system/settings/index'],
                    'method' => 'post'
                ],
            ]) ?>
        </div>
    </div>
    <!-- /control sidebar -->
    <?php endif; ?>
</div>
<?php $this->endContent(); ?>
