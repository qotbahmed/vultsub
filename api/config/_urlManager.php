<?php

return [
    // 'class' => 'yii\rest\UrlManager',
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,

    // merge common urls with doctor and patient
    'rules' =>
    \yii\helpers\ArrayHelper::merge(
        [
            ['pattern' => '/', 'route' => 'site/index'],
            ['pattern' => 'settings', 'route' => 'site/settings'],
            ['pattern' => 'terms', 'route' => 'site/terms'],
            ['pattern' => 'support-team', 'route' => 'site/support-team'],
            ['pattern' => '/user/login', 'route' => 'user/login'],
            ['pattern' => '/user/signup', 'route' => 'user/signup'],
            ['pattern' => '/user/verify', 'route' => 'user/verify'],
            ['pattern' => '/user/resend-verify-code', 'route' => 'user/resend-verify-code'],
            ['pattern' => '/user/request-reset-password', 'route' => 'user/request-reset-password'],
            ['pattern' => '/user/verify-reset-password-token', 'route' => 'user/verify-reset-password-token'],
            ['pattern' => '/user/reset-password', 'route' => 'user/reset-password'],
            ['pattern' => 'contractors', 'route' => 'company-contracts'],
            [
                'class' => 'yii\rest\UrlRule', 'controller' => 'contactus', 'only' => ['create', 'options'], 'extraPatterns' => [
                    'POST ' => 'create',
                ], 'pluralize' => false,
            ],

            /**************************  User Signup   ********************************************/
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'user',
                'only' => ['login',  'signup', 'verify', 'request-reset-password', 'verify-reset-password-token', 'reset-password', 'options','test'],
                'extraPatterns' => [
                    'POST signup' => 'signup',
                    'POST login' => 'login',
                    'POST verify' => 'verify',
                    'POST request-reset-password' => 'request-reset-password',
                    'POST verify-reset-password-token' => 'verify-reset-password-token',
                    'POST reset-password' => 'reset-password',
                    'GET test' => 'test',
                ], 'pluralize' => false,
            ],

            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'profile',
                'only' => ['index', 'update', 'check-point', 'change-password', 'options',
                ],
                'extraPatterns' => [
                    'GET /' => 'index',
                    'PUT /' => 'update',
                    'PUT change-password' => 'change-password',
                    'PUT check-point' => 'check-point',


                ],
                'pluralize' => false,
            ],    [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'points-logs',
                'only' => ['index', 'options',
                ],
                'extraPatterns' => [
                    'GET /' => 'index',
                ],
                'pluralize' => false,
            ],

            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'lookups',
                'only' => ['country','business-sectors','terms-conditions','company-sizes','sponsors','settings'],
                'extraPatterns' => [
                    'GET terms-conditions' => 'terms-conditions',
                    'GET sponsors' => 'sponsors',
                    'GET settings' => 'settings',
                ], 'pluralize' => false,
            ],

            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'faq',
                'only' => ['index', 'options'],
                'extraPatterns' => [],
                'pluralize' => false,
            ],

            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'contact-us',
                'only' => ['create', 'options'],
                'extraPatterns' => [],
                'pluralize' => false,
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'notification/notification',
                'only' => ['create', 'options','users'],
                'extraPatterns' => [
                    'GET users' => 'users', 

                ],
                'pluralize' => false,
            ],
            ['class' => 'yii\rest\UrlRule', 'controller' => 'content'
                , 'only' => ['terms-conditions']
                , 'extraPatterns' => [
                'GET terms-conditions' => 'terms-conditions',
            ]
                , 'pluralize' => false,
            ],

//            [
//                'class' => 'yii\rest\UrlRule',
//                'controller' => 'company-contracts',
//                'only' => ['index', 'add-contractor','options'],
//                'extraPatterns' => [
//                    'GET /' => 'index',
//                    'POST add-contractor' => 'add-contractor',
//                ],
//                'pluralize' => false,
//            ],

        ],
        require(__DIR__ . '/urls/_CompanyUrlManager.php'),
        require(__DIR__ . '/urls/_EmployeeUrlManager.php'),
    ),

];
