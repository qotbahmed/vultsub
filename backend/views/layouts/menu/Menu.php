<?php

use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use common\models\User;

return
    [

        [
            'label' => Yii::t('backend', 'Customers'),
            'url' => '#',
            'icon' => FAS::icon('users', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->id === 'user'),

            'visible' => (Yii::$app->user->can('administrator') || Yii::$app->user->identity->checkMenuPermissions('user_index')),

            'items' => [
                [
                    'label' => Yii::t('backend', 'Customers List'),
                    'url' => ['/user'],
                    'icon' => FAR::icon('user', ['class' => ['nav-icon']]),
                    'active' => (Yii::$app->controller->id === 'user'),
                    'visible' => (Yii::$app->user->can('user')),
                ],
            ],
        ],



        [
            'label' => Yii::t('common', 'System Management'),
            'url' => '#',
            'icon' => FAS::icon('cogs', ['class' => ['nav-icon']]), // Change icon as needed
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->module->id === 'locations' ||
                in_array(Yii::$app->controller->id, ['cities', 'districts', 'service', 'service-type', 'product', 'product-category'])),
            'items' => [

                [
                    'label' => Yii::t('common', 'Roles'),
                    'url' => ['/user-custom-role/index'],
                    'icon' => FAR::icon('user', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'user-custom-role',
                    'visible' => (Yii::$app->user->can('administrator')),
                ],


                [
                    'label' => Yii::t('common', 'Locations'),
                    'url' => '#',
                    'icon' => FAS::icon('map-marker-alt', ['class' => ['nav-icon']]),
                    'options' => ['class' => 'nav-item has-treeview'],
                    'active' => (Yii::$app->controller->module->id === 'locations' &&
                        in_array(Yii::$app->controller->id, ['cities', 'districts'])),
                    'items' => [
                        [
                            'label' => Yii::t('common', 'Cities'),
                            'url' => ['cities/index'],
                            'active' => (Yii::$app->controller->id === 'cities' && Yii::$app->controller->action->id === 'index'),
                            'icon' => FAS::icon('city', ['class' => ['nav-icon']]),
                        ],
                        [
                            'label' => Yii::t('common', 'Districts'),
                            'url' => ['districts/index'],
                            'active' => (Yii::$app->controller->id === 'districts' && Yii::$app->controller->action->id === 'index'),
                            'icon' => FAS::icon('home', ['class' => ['nav-icon']]),
                        ],
                    ]
                ],





            ],
        ],

        [
            'label' => Yii::t('backend', 'Website Content'),
            'url' => '#',
            'icon' => FAS::icon('puzzle-piece', ['class' => ['nav-icon']]),
            'options' => ['class' => 'nav-item has-treeview'],
            'active' => (Yii::$app->controller->module->id === 'faq' || Yii::$app->controller->module->id === 'page' || Yii::$app->controller->module->id === 'settings' || 'category' === Yii::$app->controller->id),
            'items' => [
                [
                    'label' => Yii::t('backend', 'Pages'),
                    'url' => ['/page/index'],
                    'icon' => FAS::icon('thumbtack', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'page',
                ],
                [
                    'label' => Yii::t('backend', 'Settings'),
                    'url' => ['/settings/index'],
                    'icon' => FAS::icon('thumbtack', ['class' => ['nav-icon']]),
                    'active' => Yii::$app->controller->id === 'settings',
                ],
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

        // [
        //     'label' => Yii::t('backend', 'Supplier'),
        //     'url' => ['/supplier/index'],
        //     'icon' => '<span class="isax isax-people"></span>',
        //     'active' => Yii::$app->controller->id === 'supplier',
        //     // 'visible' => Yii::$app->controller->MainAcadmin === 0,

        // ],
//                [
//                    'label' => Yii::t('backend', 'Contracts'),
//                    'url' => ['/contract/index'],
//                    'icon' => '<span class="isax isax-people"></span>',
//                    'active' => Yii::$app->controller->id === 'contract',
//                    // 'visible' => Yii::$app->controller->MainAcadmin === 0,
//
//                ],


    ];
?>