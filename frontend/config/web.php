<?php
$config = [
    'homeUrl' => Yii::getAlias('@frontendUrl'),
    'controllerNamespace' => 'frontend\controllers',
//    'defaultRoute' => 'profile/index',
    'bootstrap' => ['maintenance'],
    'modules' => [
        'content' => [
            'class' => backend\modules\content\Module::class,
        ],
        'widget' => [
            'class' => backend\modules\widget\Module::class,
        ],
        'file' => [
            'class' => backend\modules\file\Module::class,
        ],
        'system' => [
            'class' => backend\modules\system\Module::class,
        ],
        'translation' => [
            'class' => backend\modules\translation\Module::class,
        ],
        'rbac' => [
            'class' => backend\modules\rbac\Module::class,
            'defaultRoute' => 'rbac-auth-item/index',
        ],

    'gridview' => [
        'class' => '\kartik\grid\Module'
        // enter optional module parameters below - only if you need to
        // use your own export download action or custom translation
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
    ],
    'user' => [
        'class' => \frontend\modules\user\Module::class,
        'shouldBeActivated' => false,
        'enableLoginByPass' => false,
    ],
],
    'components' => [
    'authClientCollection' => [
        'class' => yii\authclient\Collection::class,
        'clients' => [
            'github' => [
                'class' => yii\authclient\clients\GitHub::class,
                'clientId' => env('GITHUB_CLIENT_ID'),
                'clientSecret' => env('GITHUB_CLIENT_SECRET')
            ],
            'facebook' => [
                'class' => yii\authclient\clients\Facebook::class,
                'clientId' => env('FACEBOOK_CLIENT_ID'),
                'clientSecret' => env('FACEBOOK_CLIENT_SECRET'),
                'scope' => 'email,public_profile',
                'attributeNames' => [
                    'name',
                    'email',
                    'first_name',
                    'last_name',
                ]
            ]
        ]
    ],
    'errorHandler' => [
        'errorAction' => 'site/error'
    ],
    'maintenance' => [
        'class' => common\components\maintenance\Maintenance::class,
        'enabled' => function ($app) {
            if (env('APP_MAINTENANCE') === '1') {
                return true;
            }
            return $app->keyStorage->get('frontend.maintenance') === 'enabled';
        }
    ],
    'request' => [
        'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY')
    ],
    'user' => [
        'class' => yii\web\User::class,
        'identityClass' => common\models\User::class,
        'loginUrl' => ['/user/sign-in/login'],
        'enableAutoLogin' => true,
        'as afterLogin' => common\behaviors\LoginTimestampBehavior::class
    ],
    'assetManager' => [
        'bundles' => [
            \yii\bootstrap4\BootstrapAsset::class => false,
        ]
    ]
]
];

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        'generators' => [
            'crud' => [
                'class' => yii\gii\generators\crud\Generator::class,
                'messageCategory' => 'frontend'
            ]
        ]
    ];
}

return $config;
