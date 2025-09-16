<?php

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // Home routes
        '' => 'home/index',
        'home' => 'home/index',
        'pricing' => 'home/pricing',
        'academy-simple' => 'home/academy-simple',
        'test-academy' => 'home/test-academy',
        'debug' => 'home/debug',
        'info' => 'home/info',

        // Auth routes
        'login' => 'auth/login',
        'register' => 'auth/register',
        'logout' => 'auth/logout',
        'sign-in' => 'auth/login',
        'signup' => 'auth/register',
        'unified-login' => 'auth/unified-login',

        // Dashboard routes
        'trial-dashboard' => 'dashboard/trial-dashboard',
        'admin-dashboard' => 'dashboard/admin-dashboard',
        'academy-management' => 'dashboard/academy-management',
        'players-management' => 'dashboard/players-management',

        // API routes
        'api' => 'api/index',
        'api/academy-requests' => 'api/academy-requests',
        'api/players' => 'api/players',
        'api/portal-integration' => 'api/portal-integration',
        'api/trial-management' => 'api/trial-management',

        // Legacy routes for backward compatibility
        'home.php' => 'home/index',
        'pricing.php' => 'home/pricing',
        'login.php' => 'auth/login',
        'register.php' => 'auth/register',
        'unified-login.php' => 'auth/unified-login',
    ]
];
