<?php

namespace common\services;

use Yii;
use common\models\PlayerAttendance;
use frontend\models\Player;
use common\models\SchedulesPlayer;
use common\models\SubscriptionDetails;
use yii\db\Query;

/**
 * Attendance Rate Calculation Service
 * 
 * This service handles the calculation of player attendance rates
 * based on their scheduled classes and actual attendance records.
 */
class AttendanceRateService
{
    /**
     * Calculate attendance rate for a specific player
     * 
     * @param int $playerId
     * @param int|null $subscriptionId
     * @param int|null $subDetailsId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return float
     */
    public static function calculatePlayerAttendanceRate($playerId, $subscriptionId = null, $subDetailsId = null, $startDate = null, $endDate = null)
    {
        // Get scheduled classes for the player
        $scheduledClasses = self::getScheduledClasses($playerId, $subscriptionId, $subDetailsId, $startDate, $endDate);
        
        if (empty($scheduledClasses)) {
            return 0.0;
        }
        
        // Get attended classes
        $attendedClasses = self::getAttendedClasses($playerId, $subscriptionId, $subDetailsId, $startDate, $endDate);
        
        // Calculate rate
        $totalScheduled = count($scheduledClasses);
        $totalAttended = count($attendedClasses);
        
        if ($totalScheduled == 0) {
            return 0.0;
        }
        
        return round(($totalAttended / $totalScheduled) * 100, 2);
    }
    
    /**
     * Get scheduled classes for a player
     * 
     * @param int $playerId
     * @param int|null $subscriptionId
     * @param int|null $subDetailsId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public static function getScheduledClasses($playerId, $subscriptionId = null, $subDetailsId = null, $startDate = null, $endDate = null)
    {
        $query = (new Query())
            ->from('schedules_player sp')
            ->innerJoin('subscription_details sd', 'sp.sub_details_id = sd.id')
            ->where(['sp.player_id' => $playerId]);
        
        if ($subscriptionId) {
            $query->andWhere(['sd.subscription_id' => $subscriptionId]);
        }
        
        if ($subDetailsId) {
            $query->andWhere(['sp.sub_details_id' => $subDetailsId]);
        }
        
        if ($startDate) {
            $query->andWhere(['>=', 'sp.start_date', $startDate]);
        }
        
        if ($endDate) {
            $query->andWhere(['<=', 'sp.end_date', $endDate]);
        }
        
        return $query->all();
    }
    
    /**
     * Get attended classes for a player
     * 
     * @param int $playerId
     * @param int|null $subscriptionId
     * @param int|null $subDetailsId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public static function getAttendedClasses($playerId, $subscriptionId = null, $subDetailsId = null, $startDate = null, $endDate = null)
    {
        $query = PlayerAttendance::find()
            ->where(['player_id' => $playerId])
            ->andWhere(['attend_status' => 1]); // 1 = attended, 0 = absent
        
        if ($subscriptionId) {
            $query->andWhere(['subscription_id' => $subscriptionId]);
        }
        
        if ($subDetailsId) {
            $query->andWhere(['sub_details_id' => $subDetailsId]);
        }
        
        if ($startDate) {
            $query->andWhere(['>=', 'attend_date', $startDate]);
        }
        
        if ($endDate) {
            $query->andWhere(['<=', 'attend_date', $endDate]);
        }
        
        return $query->all();
    }
    
    /**
     * Update attendance rate for a specific player
     * 
     * @param int $playerId
     * @param int|null $subscriptionId
     * @param int|null $subDetailsId
     * @return bool
     */
    public static function updatePlayerAttendanceRate($playerId, $subscriptionId = null, $subDetailsId = null)
    {
        $attendanceRate = self::calculatePlayerAttendanceRate($playerId, $subscriptionId, $subDetailsId);
        
        $player = Player::findOne($playerId);
        if ($player) {
            $player->attendance_rate = $attendanceRate;
            return $player->save(false);
        }
        
        return false;
    }
    
    /**
     * Update attendance rates for all players
     * 
     * @param int|null $academyId
     * @return int Number of players updated
     */
    public static function updateAllPlayersAttendanceRates($academyId = null)
    {
        $query = Player::find();
        
        if ($academyId) {
            $query->where(['academy_id' => $academyId]);
        }
        
        $players = $query->all();
        $updated = 0;
        
        foreach ($players as $player) {
            if (self::updatePlayerAttendanceRate($player->id)) {
                $updated++;
            }
        }
        
        return $updated;
    }
    
    /**
     * Get attendance statistics for a player
     * 
     * @param int $playerId
     * @param int|null $subscriptionId
     * @param int|null $subDetailsId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public static function getPlayerAttendanceStats($playerId, $subscriptionId = null, $subDetailsId = null, $startDate = null, $endDate = null)
    {
        $scheduledClasses = self::getScheduledClasses($playerId, $subscriptionId, $subDetailsId, $startDate, $endDate);
        $attendedClasses = self::getAttendedClasses($playerId, $subscriptionId, $subDetailsId, $startDate, $endDate);
        
        $totalScheduled = count($scheduledClasses);
        $totalAttended = count($attendedClasses);
        $totalAbsent = $totalScheduled - $totalAttended;
        $attendanceRate = $totalScheduled > 0 ? round(($totalAttended / $totalScheduled) * 100, 2) : 0.0;
        
        return [
            'total_scheduled' => $totalScheduled,
            'total_attended' => $totalAttended,
            'total_absent' => $totalAbsent,
            'attendance_rate' => $attendanceRate,
            'scheduled_classes' => $scheduledClasses,
            'attended_classes' => $attendedClasses
        ];
    }
    
    /**
     * Get academy-wide attendance statistics
     * 
     * @param int $academyId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public static function getAcademyAttendanceStats($academyId, $startDate = null, $endDate = null)
    {
        $players = Player::find()->where(['academy_id' => $academyId])->all();
        
        $totalPlayers = count($players);
        $totalAttendanceRate = 0;
        $playersWithData = 0;
        
        $playerStats = [];
        
        foreach ($players as $player) {
            $stats = self::getPlayerAttendanceStats($player->id, null, null, $startDate, $endDate);
            
            if ($stats['total_scheduled'] > 0) {
                $totalAttendanceRate += $stats['attendance_rate'];
                $playersWithData++;
            }
            
            $playerStats[] = [
                'player_id' => $player->id,
                'player_name' => $player->name,
                'attendance_rate' => $stats['attendance_rate'],
                'total_scheduled' => $stats['total_scheduled'],
                'total_attended' => $stats['total_attended']
            ];
        }
        
        $averageAttendanceRate = $playersWithData > 0 ? round($totalAttendanceRate / $playersWithData, 2) : 0.0;
        
        return [
            'total_players' => $totalPlayers,
            'players_with_data' => $playersWithData,
            'average_attendance_rate' => $averageAttendanceRate,
            'player_stats' => $playerStats
        ];
    }
    
    /**
     * Mark player attendance for a specific class
     * 
     * @param int $playerId
     * @param int $scheduleId
     * @param int $subscriptionId
     * @param int $subDetailsId
     * @param int $academySportId
     * @param string $sportName
     * @param int $day
     * @param string $startTime
     * @param string $endTime
     * @param int $attendStatus (1 = attended, 0 = absent)
     * @return bool
     */
    public static function markPlayerAttendance($playerId, $scheduleId, $subscriptionId, $subDetailsId, $academySportId, $sportName, $day, $startTime, $endTime, $attendStatus = 1)
    {
        $attendance = new PlayerAttendance();
        $attendance->player_id = $playerId;
        $attendance->subscription_id = $subscriptionId;
        $attendance->sub_details_id = $subDetailsId;
        $attendance->academy_sport_id = $academySportId;
        $attendance->sport_name = $sportName;
        $attendance->day = $day;
        $attendance->start_time = $startTime;
        $attendance->end_time = $endTime;
        $attendance->attend_date = date('Y-m-d');
        $attendance->attend_status = $attendStatus;
        $attendance->created_at = date('Y-m-d H:i:s');
        
        if ($attendance->save()) {
            // Update player's attendance rate
            self::updatePlayerAttendanceRate($playerId, $subscriptionId, $subDetailsId);
            return true;
        }
        
        return false;
    }
}
