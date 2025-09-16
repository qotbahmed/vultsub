<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "classes_log".
 *
 * @property int $id
 * @property int $subscription_details_id
 * @property int|null $extra_classes
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $comment
 * @property int|null $schplayer_id
 *
 * @property SubscriptionDetails $subscriptionDetails
 * @property SchedulesPlayer $schedulesPlayer
 */
class ClassesLog extends \yii\db\ActiveRecord
{
    public $player_id; 
    public $sports_id; 
    public $packages_id;

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classes_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subscription_details_id','extra_classes'], 'required'],
            [['subscription_details_id', 'extra_classes', 'created_by', 'updated_by', 'schplayer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['subscription_details_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubscriptionDetails::class, 'targetAttribute' => ['subscription_details_id' => 'id']],
            [['schplayer_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchedulesPlayer::class, 'targetAttribute' => ['schplayer_id' => 'id']],
            [['player_id'], 'safe'], 
            [['sports_id'], 'safe'], 
            [['packages_id'], 'safe'], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'subscription_details_id' => Yii::t('common', 'Subscription Details ID'),
            'extra_classes' => Yii::t('common', 'Extra Classes'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'comment' => Yii::t('common', 'Comment'),
            'schplayer_id' => Yii::t('common', 'Schedule Player ID'),
        ];
    }

    /**
     * Gets query for [[SubscriptionDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionDetails()
    {
        return $this->hasOne(SubscriptionDetails::class, ['id' => 'subscription_details_id']);
    }

    /**
     * Gets query for [[SchedulesPlayer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulesPlayer()
    {
        return $this->hasOne(SchedulesPlayer::class, ['id' => 'schplayer_id']);
    }

    /**
     * Gets query for the user who created the entry.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    
        $selectedSchedules = Yii::$app->request->post('ClassesLog')['schplayer_ids'];
    
        foreach ($selectedSchedules as $scheduleId) {
            $lastSchedule = SchedulesPlayer::find()->where(['id' => $scheduleId])->orderBy(['date' => SORT_DESC])->one();
            
            if ($lastSchedule) {
                $lastPlayerSchedule = SchedulesPlayer::find()
                    ->where(['player_id' => $lastSchedule->player_id, 'day' => $lastSchedule->day])
                    ->orderBy(['date' => SORT_DESC])
                    ->one();
    
                $newDate = $lastPlayerSchedule 
                    ? date('Y-m-d', strtotime($lastPlayerSchedule->date . ' +1 week')) 
                    : date('Y-m-d', strtotime($lastSchedule->date . ' +1 week'));
    
                $academy_id = $lastSchedule->academy_id;
                $subscription_id = $lastSchedule->subscription_id;
                $academy_sport_id = $lastSchedule->academy_sport_id;
    
                $academySport = AcademySport::findOne($academy_sport_id);
                $sports_id = $academySport ? $academySport->sport_id : null;
    
                if (!$sports_id) {
                    Yii::error('Failed to retrieve sport ID from academy_sport', __METHOD__);
                    continue; 
                }
    
                $newSchedule = new SchedulesPlayer();
                $newSchedule->player_id = $lastSchedule->player_id;
                $newSchedule->date = $newDate;
                $newSchedule->day = $lastSchedule->day;
                $newSchedule->start_time = $lastSchedule->start_time;
                $newSchedule->end_time = $lastSchedule->end_time;
                $newSchedule->schedules_id = $lastSchedule->schedules_id;
                $newSchedule->academy_id = $academy_id;
                $newSchedule->subscription_id = $subscription_id;
                $newSchedule->academy_sport_id = $academy_sport_id;
    
                if (!$newSchedule->save()) {
                    Yii::error('Failed to save new schedule: ' . print_r($newSchedule->errors, true), __METHOD__);
                }
    
                $latestClass = SchedulesPlayer::find()
                    ->where(['player_id' => $lastSchedule->player_id, 'academy_sport_id' => $academy_sport_id])
                    ->orderBy(['date' => SORT_DESC])
                    ->one();
    
                if ($latestClass) {
                    $subscriptionDetails = SubscriptionDetails::find()
                        ->where([
                            'player_id' => $lastSchedule->player_id, 
                            'subscription_id' => $subscription_id,
                            'sports_id' => $sports_id 
                        ])
                        ->orderBy(['end_date' => SORT_DESC])
                        ->one();
    
                    if ($subscriptionDetails) {
                        $subscriptionDetails->end_date = $latestClass->date;
                        if (!$subscriptionDetails->save()) {
                            Yii::error('Failed to update subscription details: ' . print_r($subscriptionDetails->errors, true), __METHOD__);
                        }
                    }
    
                    $subscription = Subscription::find()
                        ->where(['id' => $subscription_id])
                        ->one();
    
                    if ($subscription) {
                        $subscription->end_date = $latestClass->date;
                        if (!$subscription->save()) {
                            Yii::error('Failed to update subscription: ' . print_r($subscription->errors, true), __METHOD__);
                        }
                    }
                }
            }
        }
    }
    
    public function validateExtraClasses($uniqueDaysCount)
    {
        if ($this->extra_classes > $uniqueDaysCount) {
            $this->addError('extra_classes', Yii::t('common', 'The number of classes cannot exceed the number of unique days.'));
            return false;
        }
        return true;
    }
    
    
}
