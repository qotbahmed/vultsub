<?php

return [
    'id' => 'vult-academy',
    'name' => 'Vult Academy Management',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ar',
    'sourceLanguage' => 'en',
    'timeZone' => 'Asia/Riyadh',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'your-cookie-validation-key-here',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => $_ENV['DB_DSN'] ?? 'mysql:host=database;port=3306;dbname=vult',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? 'root',
            'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
            'tablePrefix' => $_ENV['DB_TABLE_PREFIX'] ?? '',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'dashboard' => 'dashboard/index',
                'players' => 'players/index',
                'teams' => 'teams/index',
                'schedule' => 'schedule/index',
                'reports' => 'reports/index',
                'settings' => 'settings/index',
            ],
        ],
    ],
    'params' => [
        'trialDays' => $_ENV['TRIAL_DAYS'] ?? 7,
        'trialNotificationDays' => $_ENV['TRIAL_NOTIFICATION_DAYS'] ?? 2,
    ],
];
