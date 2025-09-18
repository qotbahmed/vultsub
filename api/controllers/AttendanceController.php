<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;
use common\services\AttendanceRateService;
use common\models\PlayerAttendance;
use frontend\models\Player;
use common\models\Academies;

/**
 * Attendance Management API Controller
 */
class AttendanceController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        
        // Add authentication filter
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options']
        ];
        
        return $behaviors;
    }
    
    /**
     * Get player attendance rate
     * 
     * @param int $playerId
     * @return array
     */
    public function actionPlayerRate($playerId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $player = Player::findOne($playerId);
            if (!$player) {
                return [
                    'success' => false,
                    'message' => 'Player not found',
                    'data' => null
                ];
            }
            
            $attendanceRate = AttendanceRateService::calculatePlayerAttendanceRate($playerId);
            $stats = AttendanceRateService::getPlayerAttendanceStats($playerId);
            
            return [
                'success' => true,
                'message' => 'Attendance rate retrieved successfully',
                'data' => [
                    'player_id' => $playerId,
                    'player_name' => $player->name,
                    'attendance_rate' => $attendanceRate,
                    'stats' => $stats
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error retrieving attendance rate: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
    
    /**
     * Get academy attendance statistics
     * 
     * @param int $academyId
     * @return array
     */
    public function actionAcademyStats($academyId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $academy = Academies::findOne($academyId);
            if (!$academy) {
                return [
                    'success' => false,
                    'message' => 'Academy not found',
                    'data' => null
                ];
            }
            
            $stats = AttendanceRateService::getAcademyAttendanceStats($academyId);
            
            return [
                'success' => true,
                'message' => 'Academy attendance statistics retrieved successfully',
                'data' => [
                    'academy_id' => $academyId,
                    'academy_name' => $academy->name,
                    'stats' => $stats
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error retrieving academy stats: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
    
    /**
     * Mark player attendance
     * 
     * @return array
     */
    public function actionMarkAttendance()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $request = Yii::$app->request;
            $playerId = $request->post('player_id');
            $scheduleId = $request->post('schedule_id');
            $subscriptionId = $request->post('subscription_id');
            $subDetailsId = $request->post('sub_details_id');
            $academySportId = $request->post('academy_sport_id');
            $sportName = $request->post('sport_name');
            $day = $request->post('day');
            $startTime = $request->post('start_time');
            $endTime = $request->post('end_time');
            $attendStatus = $request->post('attend_status', 1);
            
            // Validate required fields
            $requiredFields = ['player_id', 'subscription_id', 'sub_details_id', 'academy_sport_id', 'sport_name', 'day', 'start_time', 'end_time'];
            foreach ($requiredFields as $field) {
                if (empty($$field)) {
                    return [
                        'success' => false,
                        'message' => "Required field '{$field}' is missing",
                        'data' => null
                    ];
                }
            }
            
            $result = AttendanceRateService::markPlayerAttendance(
                $playerId,
                $scheduleId,
                $subscriptionId,
                $subDetailsId,
                $academySportId,
                $sportName,
                $day,
                $startTime,
                $endTime,
                $attendStatus
            );
            
            if ($result) {
                // Get updated attendance rate
                $attendanceRate = AttendanceRateService::calculatePlayerAttendanceRate($playerId, $subscriptionId, $subDetailsId);
                
                return [
                    'success' => true,
                    'message' => 'Attendance marked successfully',
                    'data' => [
                        'player_id' => $playerId,
                        'attendance_rate' => $attendanceRate,
                        'attend_status' => $attendStatus
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to mark attendance',
                    'data' => null
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking attendance: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
    
    /**
     * Update all attendance rates
     * 
     * @return array
     */
    public function actionUpdateRates()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $academyId = Yii::$app->request->post('academy_id');
            
            $updated = AttendanceRateService::updateAllPlayersAttendanceRates($academyId);
            
            return [
                'success' => true,
                'message' => 'Attendance rates updated successfully',
                'data' => [
                    'updated_players' => $updated,
                    'academy_id' => $academyId
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating attendance rates: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
    
    /**
     * Get attendance history for a player
     * 
     * @param int $playerId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function actionPlayerHistory($playerId, $startDate = null, $endDate = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $player = Player::findOne($playerId);
            if (!$player) {
                return [
                    'success' => false,
                    'message' => 'Player not found',
                    'data' => null
                ];
            }
            
            $query = PlayerAttendance::find()
                ->where(['player_id' => $playerId])
                ->orderBy(['attend_date' => SORT_DESC]);
            
            if ($startDate) {
                $query->andWhere(['>=', 'attend_date', $startDate]);
            }
            
            if ($endDate) {
                $query->andWhere(['<=', 'attend_date', $endDate]);
            }
            
            $attendanceRecords = $query->all();
            $formattedRecords = [];
            
            foreach ($attendanceRecords as $record) {
                $formattedRecords[] = [
                    'id' => $record->id,
                    'sport_name' => $record->sport_name,
                    'day' => $record->day,
                    'start_time' => $record->start_time,
                    'end_time' => $record->end_time,
                    'attend_date' => $record->attend_date,
                    'attend_status' => $record->attend_status,
                    'status_text' => $record->getAttendanceStatusText(),
                    'created_at' => $record->created_at
                ];
            }
            
            return [
                'success' => true,
                'message' => 'Attendance history retrieved successfully',
                'data' => [
                    'player_id' => $playerId,
                    'player_name' => $player->name,
                    'records' => $formattedRecords,
                    'total_records' => count($formattedRecords)
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error retrieving attendance history: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
