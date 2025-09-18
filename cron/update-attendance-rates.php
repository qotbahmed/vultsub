<?php
/**
 * Attendance Rate Update Cron Job
 * 
 * This script updates attendance rates for all players
 * Run this script daily to keep attendance rates current
 * 
 * Usage: php update-attendance-rates.php [academy_id]
 */

// Set time limit for long-running script
set_time_limit(300); // 5 minutes

// Include Yii2 bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../common/config/bootstrap.php';
require_once __DIR__ . '/../console/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../common/config/main.php',
    require __DIR__ . '/../console/config/main.php'
);

$application = new yii\console\Application($config);

use common\services\AttendanceRateService;
use frontend\models\Player;
use common\models\Academies;

echo "Starting attendance rate update process...\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // Get academy ID from command line argument if provided
    $academyId = isset($argv[1]) ? (int)$argv[1] : null;
    
    if ($academyId) {
        // Update specific academy
        $academy = Academies::findOne($academyId);
        if (!$academy) {
            echo "Error: Academy with ID {$academyId} not found.\n";
            exit(1);
        }
        
        echo "Updating attendance rates for academy: {$academy->name} (ID: {$academyId})\n";
        $updated = AttendanceRateService::updateAllPlayersAttendanceRates($academyId);
        echo "Updated {$updated} players in academy {$academyId}\n";
        
    } else {
        // Update all academies
        $academies = Academies::find()->all();
        $totalUpdated = 0;
        
        foreach ($academies as $academy) {
            echo "Processing academy: {$academy->name} (ID: {$academy->id})\n";
            $updated = AttendanceRateService::updateAllPlayersAttendanceRates($academy->id);
            $totalUpdated += $updated;
            echo "Updated {$updated} players in academy {$academy->id}\n\n";
        }
        
        echo "Total players updated across all academies: {$totalUpdated}\n";
    }
    
    // Generate summary report
    echo "\n=== ATTENDANCE RATE SUMMARY ===\n";
    
    if ($academyId) {
        $stats = AttendanceRateService::getAcademyAttendanceStats($academyId);
        echo "Academy: {$academy->name}\n";
        echo "Total Players: {$stats['total_players']}\n";
        echo "Players with Data: {$stats['players_with_data']}\n";
        echo "Average Attendance Rate: {$stats['average_attendance_rate']}%\n";
        
        // Show top performers
        echo "\nTop 5 Performers:\n";
        $topPerformers = array_slice($stats['player_stats'], 0, 5);
        foreach ($topPerformers as $player) {
            echo "- {$player['player_name']}: {$player['attendance_rate']}% ({$player['total_attended']}/{$player['total_scheduled']})\n";
        }
        
    } else {
        // Show overall statistics
        $allPlayers = Player::find()->all();
        $playersWithData = 0;
        $totalAttendanceRate = 0;
        
        foreach ($allPlayers as $player) {
            if ($player->attendance_rate > 0) {
                $playersWithData++;
                $totalAttendanceRate += $player->attendance_rate;
            }
        }
        
        $averageRate = $playersWithData > 0 ? round($totalAttendanceRate / $playersWithData, 2) : 0;
        
        echo "Total Players: " . count($allPlayers) . "\n";
        echo "Players with Data: {$playersWithData}\n";
        echo "Average Attendance Rate: {$averageRate}%\n";
    }
    
    echo "\nAttendance rate update completed successfully!\n";
    echo "End Time: " . date('Y-m-d H:i:s') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
