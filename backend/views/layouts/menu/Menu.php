<?php

use kartik\helpers\Html;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use common\models\User;

$user = Yii::$app->user->identity;
$profileImage = $user->userProfile->getAvatar()
    ? Html::img($user->userProfile->getAvatar(), ['class' => 'user-avatar'])
    : Html::img('/img/avatar.svg', ['class' => 'user-avatar']); // Default avatar

return
    [
        [
            'label' => Yii::t('backend', 'Dashboard'),
            'url' => '/site',
            'icon' => FAS::icon('fas fa-chart-bar', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => Yii::$app->controller->id === 'site'
        ],

        [
            'label' => Yii::t('backend', 'Sponsor Logs'),
            'url' => '/sponsor-log',
            'icon' => FAS::icon('gift', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => Yii::$app->controller->id === 'sponsor-log'
        ],
        [
            'label' => Yii::t('backend', 'Sponsors'),
            'url' => ['/sponsors'],
            'icon' => FAS::icon('star', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->id === 'sponsors'),

            'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('user_sponsors')),

        ],


        [
            'label' => Yii::t('backend', 'Student Management'),
            'url' => ['/user'],
            'icon' => FAS::icon('users', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->id === 'user'),

            'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('user_index')),
        ],
        [
            'label' => Yii::t('backend', 'Customers'),
            'url' => ['/dashboard-managers'],
            'icon' => FAS::icon('users', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->id === 'dashboard-managers'),

            'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('user_index')),
        ],
        [
            'label' => Yii::t('backend', 'System settings'),
            'url' => '#',
            'icon' => FAS::icon('puzzle-piece', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->module->id === 'settings' || Yii::$app->controller->id === 'category' || Yii::$app->controller->id === 'faq'),
            'items' => [
                [
                    'label' => Yii::t('backend', 'Pages'),
                    'url' => ['/page/index'],
                    'icon' => FAS::icon('thumbtack', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'page',
                ],
                [
                    'label' => Yii::t('backend', 'Settings'),
                    'url' => ['/settings/index?tab=privacy'],
                    'icon' => FAS::icon('thumbtack', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'settings'],
                [
                    'label' => Yii::t('backend', 'FAQs'),
                    'url' => '#',
                    'icon' => FAS::icon('question', ['class' => ['nav-icon']]),
                    'options' => ['class' => 'nav-item has-treeview'],
                    'active' => (Yii::$app->controller->id === 'faq' || Yii::$app->controller->id === 'category'),

                    'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('category_index') || Yii::$app->user->identity->checkMenuPermissions('faq_index')),


                    'items' => [
                        [
                            'label' => Yii::t('backend', 'Category'),
                            'url' => ['/category/index'],
                            'icon' => '<i class="fas fa-tags" ></i>',
                            'active' => (Yii::$app->controller->id === 'category'),

                            'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('category_index')),
                        ],
                        [
                            'label' => Yii::t('backend', 'FAQs'),
                            'url' => ['/faq/index'],
                            'icon' => '<i class="fas fa-question-circle nav-icon"></i>',
                            'active' => (Yii::$app->controller->id === 'faq'),


                            'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('faq_index')),
                        ],
                    ]
                ],
            ],
        ],


//        [
//            'label' => Yii::t('common', 'System Management'),
//            'url' => '#',
//            'icon' => FAS::icon('cogs', ['class' => ['nav-icon']]), // Change icon as needed
//            'options' => ['class' => 'nav-item has-treeview'],
//            'active' => (Yii::$app->controller->module->id === 'locations' ||
//                in_array(Yii::$app->controller->id, ['cities', 'districts', 'service', 'service-type', 'product', 'product-category'])),
//            'items' => [
//
//                [
//                    'label' => Yii::t('common', 'Roles'),
//                    'url' => ['/user-custom-role/index'],
//                    'icon' => FAR::icon('user', ['class' => ['nav-icon']]),
//                    'active' => Yii::$app->controller->id === 'user-custom-role',
//                    'visible' => (Yii::$app->user->can('administrator')),
//                ],
//
//
//                [
//                    'label' => Yii::t('common', 'Locations'),
//                    'url' => '#',
//                    'icon' => FAS::icon('map-marker-alt', ['class' => ['nav-icon']]),
//                    'options' => ['class' => 'nav-item has-treeview'],
//                    'active' => (Yii::$app->controller->module->id === 'locations' &&
//                        in_array(Yii::$app->controller->id, ['cities', 'districts'])),
//                    'items' => [
//                        [
//                            'label' => Yii::t('common', 'Cities'),
//                            'url' => ['cities/index'],
//                            'active' => (Yii::$app->controller->id === 'cities' && Yii::$app->controller->action->id === 'index'),
//                            'icon' => FAS::icon('city', ['class' => ['nav-icon']]),
//                        ],
//                        [
//                            'label' => Yii::t('common', 'Districts'),
//                            'url' => ['districts/index'],
//                            'active' => (Yii::$app->controller->id === 'districts' && Yii::$app->controller->action->id === 'index'),
//                            'icon' => FAS::icon('home', ['class' => ['nav-icon']]),
//                        ],
//                    ]
//                ],
//
//
//
//
//
//            ],
//        ],

            // [
            //     'label' => Yii::t('backend', 'Supplier'),
            //     'url' => ['/supplier/index'],
            //     'icon' => '<span class="isax isax-people"></span>',
            //     'active' => Yii::$app->controller->id === 'supplier',
            //     // 'visible' => Yii::$app->controller->MainAcadmin === 0,

            // ],
//
//                [
//                    'label' => Yii::t('backend', 'Contracts'),
//                    'url' => ['/contract/index'],
//                    'icon' => '<span class="isax isax-people"></span>',
//                    'active' => Yii::$app->controller->id === 'contract',
//                    // 'visible' => Yii::$app->controller->MainAcadmin === 0,
//
//                ],
            //  User Profile

        [
            'label' => '<div class="user-menu">'
                . $profileImage .
                ' <span class="user-name">' . Html::encode($user->userProfile->getFullName()) . '</span>
</div>',
            'encode' => false, // Allows HTML rendering
            'url' => '#',
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => Yii::$app->controller->id === 'profile',
            'items' => [
                [
                    'label' => Yii::t('backend', 'Profile'),
                    'url' => ['/sign-in/profile'],
                    'icon' => FAS::icon('user', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'sign-in' && Yii::$app->controller->action->id === 'profile',
                ],
                [
                    'label' => Yii::t('backend', 'Account'),
                    'url' => ['/sign-in/account'],
                    'icon' => FAS::icon('cog', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'sign-in' && Yii::$app->controller->action->id === 'account',
                ],
                [
                    'label' => Yii::t('backend', 'Logout'),
                    'url' => ['/sign-in/logout'],
                    'icon' => FAS::icon('sign-out', ['class' => 'nav-icon']), // Keep the icon
                    'encode' => false, // Ensure Yii does not escape the icon
                    'options' => ['class' => 'nav-item'],
                    'template' => Html::a(
                        FAS::icon('sign-out') . ' ' . Yii::t('backend', 'Logout'),
                        ['/sign-in/logout'],
                        [
                            'class' => 'nav-link',
                            'data-method' => 'post',
                        ]
                    ),
                ],


            ],
        ],

    ];
?>