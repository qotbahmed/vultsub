<?php

// Vult Academy Management System
// Entry point for academy subdomain

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$config = require __DIR__ . '/../../academy/config/main.php';

// Create and run the application
(new yii\web\Application($config))->run();
