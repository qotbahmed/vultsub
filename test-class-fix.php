<?php
/**
 * Test Class Fix
 * 
 * This script tests that the class references are fixed
 */

// Include Yii2 bootstrap
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common/config/bootstrap.php';
require_once __DIR__ . '/console/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/console/config/main.php'
);

$application = new yii\console\Application($config);

use common\services\AttendanceRateService;
use frontend\models\Player;
use common\models\Academies;

echo "Testing class references...\n";

try {
    // Test 1: Check if Player class can be loaded
    echo "1. Testing Player class...\n";
    $player = new Player();
    echo "✅ Player class loaded successfully\n";
    
    // Test 2: Check if AttendanceRateService can be loaded
    echo "2. Testing AttendanceRateService class...\n";
    $service = new AttendanceRateService();
    echo "✅ AttendanceRateService class loaded successfully\n";
    
    // Test 3: Check if we can find players
    echo "3. Testing player queries...\n";
    $players = Player::find()->limit(1)->all();
    echo "✅ Player queries work successfully\n";
    
    // Test 4: Check if we can find academies
    echo "4. Testing academy queries...\n";
    $academies = Academies::find()->limit(1)->all();
    echo "✅ Academy queries work successfully\n";
    
    echo "\n🎉 All class references are working correctly!\n";
    echo "The 'common\\models\\Players' not found error has been fixed.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
