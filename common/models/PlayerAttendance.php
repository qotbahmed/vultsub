<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_attendance".
 *
 * @property int $id
 * @property int $subscription_id
 * @property int $player_id
 * @property string $sport_name
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property string|null $attend_date
 * @property string|null $created_at
 * @property int|null $schplayer_id
 * @property int $sub_details_id
 * @property array $player_ids
 * @property int $academy_sport_id  
 * @property int|null $attend_status  
 * @property User $player
 * @property SchedulesPlayer $schplayer
 * @property Subscription $subscription
 * @property SubscriptionDetails $subscriptionDetails
 * @property AcademySport $academySport  
 */
class PlayerAttendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['player_id', 'sport_name', 'day', 'start_time', 'end_time', 'academy_sport_id'], 'required'],
            [['subscription_id', 'player_id', 'schplayer_id', 'sub_details_id', 'academy_sport_id', 'attend_status'], 'integer'],
            [['start_time', 'end_time', 'attend_date', 'created_at'], 'safe'],
            [['sport_name'], 'string', 'max' => 255],
            [['day'], 'integer'],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
            [['schplayer_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchedulesPlayer::class, 'targetAttribute' => ['schplayer_id' => 'id']],
            [['sub_details_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubscriptionDetails::class, 'targetAttribute' => ['sub_details_id' => 'id']],
            [['academy_sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySport::class, 'targetAttribute' => ['academy_sport_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'subscription_id' => Yii::t('common', 'Subscription ID'),
            'player_id' => Yii::t('common', 'Player ID'),
            'sport_name' => Yii::t('common', 'Sport Name'),
            'day' => Yii::t('common', 'Day'),
            'start_time' => Yii::t('common', 'Start Time'),
            'end_time' => Yii::t('common', 'End Time'),
            'attend_date' => Yii::t('common', 'Attend Date'),
            'created_at' => Yii::t('common', 'Created At'),
            'schplayer_id' => Yii::t('common', 'Schplayer ID'),
            'sub_details_id' => Yii::t('common', 'Subscription Details ID'),
            'player_ids' => Yii::t('common', 'Player IDs'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
            'attend_status' => Yii::t('common', 'Attendance Status'),
        ];
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'player_id']);
    }

    /**
     * Gets query for [[Schplayer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchplayer()
    {
        return $this->hasOne(SchedulesPlayer::class, ['id' => 'schplayer_id']);
    }

    /**
     * Gets query for [[Subscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
    }

    /**
     * Gets query for [[SubscriptionDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionDetails()
    {
        return $this->hasOne(SubscriptionDetails::class, ['id' => 'sub_details_id']);
    }

    /**
     * Gets query for [[AcademySport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySport()
    {
        return $this->hasOne(AcademySport::class, ['id' => 'academy_sport_id']);  // العلاقة الجديدة مع AcademySport
    }

    /**
     * Loads the model with given data.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }


    public static function getAttendedClasses($player_id, $subscription_id, $sub_details_id)
    {
        return static::find()
            ->where(['player_id' => $player_id, 'subscription_id' => $subscription_id, 'sub_details_id' => $sub_details_id])
            ->all();
    }

    /**
     * Saves the model and related models if any.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     *
     * @return bool Whether the deletion was successful.
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert && $this->sub_details_id) {
            $subscriptionDetails = $this->subscriptionDetails;
            if ($subscriptionDetails) {
                // Update player attendance rate after saving attendance record
                \common\services\AttendanceRateService::updatePlayerAttendanceRate(
                    $this->player_id, 
                    $this->subscription_id, 
                    $this->sub_details_id
                );
            }
        }
    }
    
    /**
     * Calculate attendance rate for this player
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @return float
     */
    public function calculateAttendanceRate($startDate = null, $endDate = null)
    {
        return \common\services\AttendanceRateService::calculatePlayerAttendanceRate(
            $this->player_id,
            $this->subscription_id,
            $this->sub_details_id,
            $startDate,
            $endDate
        );
    }
    
    /**
     * Get attendance statistics for this player
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getAttendanceStats($startDate = null, $endDate = null)
    {
        return \common\services\AttendanceRateService::getPlayerAttendanceStats(
            $this->player_id,
            $this->subscription_id,
            $this->sub_details_id,
            $startDate,
            $endDate
        );
    }
    
    /**
     * Mark attendance for this player
     * 
     * @param int $attendStatus (1 = attended, 0 = absent)
     * @return bool
     */
    public function markAttendance($attendStatus = 1)
    {
        $this->attend_status = $attendStatus;
        $this->attend_date = date('Y-m-d');
        $this->created_at = date('Y-m-d H:i:s');
        
        return $this->save();
    }
    
    /**
     * Get attendance rate percentage with formatting
     * 
     * @return string
     */
    public function getFormattedAttendanceRate()
    {
        $rate = $this->calculateAttendanceRate();
        return number_format($rate, 1) . '%';
    }
    
    /**
     * Check if player attended this class
     * 
     * @return bool
     */
    public function isAttended()
    {
        return $this->attend_status == 1;
    }
    
    /**
     * Get attendance status text
     * 
     * @return string
     */
    public function getAttendanceStatusText()
    {
        return $this->isAttended() ? 'حضر' : 'غاب';
    }
}
