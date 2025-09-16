<?php
$config = [
    'homeUrl' => Yii::getAlias('@frontendUrl'),
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'home/index',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'], // Ensure 'info' and 'trace' are included
                    'categories' => ['application'], // Use 'application' for general logging
                    'logFile' => '@runtime/logs/app.log', // Define the log file path
                    'logVars' => [], // Optionally, prevent logging of superglobals like $_GET, $_POST, etc.
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY'),
            'baseUrl' => env('FRONTEND_BASE_URL'),
        ],
        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => common\models\User::class,
            'loginUrl' => ['sign-in/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => common\behaviors\LoginTimestampBehavior::class,
        ],
    ],

    'modules' => [
        'content' => [
            'class' => frontend\modules\content\Module::class,
        ],
        'widget' => [
            'class' => frontend\modules\widget\Module::class,
        ],
        'file' => [
            'class' => frontend\modules\file\Module::class,
        ],
        'system' => [
            'class' => frontend\modules\system\Module::class,
        ],
        'translation' => [
            'class' => frontend\modules\translation\Module::class,
        ],
        'rbac' => [
            'class' => frontend\modules\rbac\Module::class,
            'defaultRoute' => 'rbac-auth-item/index',
        ],

        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],

        'datecontrol' => [
            'class' => '\kartik\datecontrol\Module',
            // see settings on http://demos.krajee.com/datecontrol#module
        ],
        // If you use tree table
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            // see settings on http://demos.krajee.com/tree-manager#module
        ],
        
        'repeater' => [
            'class' => \prokhonenkov\repeater\Repeater::class,
        ],
    ],
    'bootstrap' => [
        'repeater', // add module id to bootstrap for proper aliases and url routes binding
    ],
    'as globalAccess' => [
        'class' => common\behaviors\GlobalAccessBehavior::class,
        'rules' => [
            // Public pages - no authentication required
            [
                'controllers' => ['home'],
                'allow' => true,
                'roles' => ['?', '@'],
            ],
            [
                'controllers' => ['auth'],
                'allow' => true,
                'roles' => ['?', '@'],
            ],
            [
                'controllers' => ['site'],
                'allow' => true,
                'roles' => ['?', '@'],
            ],
            [
                'controllers' => ['debug/default'],
                'allow' => true,
                'roles' => ['?'],
            ],
            // Authentication specific rules
            [
                'controllers' => ['sign-in'],
                'allow' => true,
                'roles' => ['?'],
                'actions' => ['login'],
            ],
            [
                'controllers' => ['sign-in'],
                'allow' => true,
                'roles' => ['@'],
                'actions' => ['logout'],
            ],
            // Dashboard pages - authentication required
            [
                'controllers' => ['dashboard'],
                'allow' => true,
                'roles' => ['@'],
            ],
            // Admin pages - specific roles required
            [
                'controllers' => ['user'],
                'allow' => true,
                'roles' => ['administrator'],
            ],
            [
                'controllers' => ['user'],
                'allow' => false,
            ],
            // Default rule - require authentication for other controllers
            [
                'allow' => true,
                'roles' => ['manager', 'administrator', 'customRole'],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        'generators' => [
            'crud' => [
                'class' => yii\gii\generators\crud\Generator::class,
                'templates' => [
                    'yii2-starter-kit' => Yii::getAlias('@frontend/views/_gii/templates'),
                ],
                'template' => 'yii2-starter-kit',
                'messageCategory' => 'frontend',
            ],
        ],
    ];
}

return $config;
