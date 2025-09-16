<?php

return [
    'id' => 'vult-saas-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY', 'frontend-cookie-key'),
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'frontend-session',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'signup' => 'site/signup',
                'login' => 'site/login',
                'pricing' => 'site/pricing',
                'features' => 'site/features',
                'contact' => 'site/contact',
                'verify-email/<token:\w+>' => 'site/verify-email',
                'reset-password/<token:\w+>' => 'site/reset-password',
            ],
        ],
    ],
    'params' => require __DIR__ . '/params.php',
];
