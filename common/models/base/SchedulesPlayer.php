<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\SchedulesPlayerQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use common\models\PlayerAttendance;
use common\models\UserProfile;
use common\models\SubscriptionDetails;
use common\models\Subscription;


/**
 * This is the base model class for table "schedules_player".
 *
 * @property integer $id
 * @property integer $day
 * @property string $start_time
 * @property string $end_time
 * @property string $date
 * @property integer $schedules_id
 * @property string $academy_id
 * @property integer $subscription_id
 * @property integer $player_id
 * @property integer $academy_sport_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $subscription_detail_id
 *
 * @property \common\models\Academies $academy
 * @property \common\models\AcademySport $academySport
 * @property \common\models\Schedules $schedules
 * @property \common\models\User $player
 */
class SchedulesPlayer extends ActiveRecord
{

    use RelationTrait;

public $qr_url;
    private $_isRegistered;
    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            'academy',
            'academySport',
            'schedules',
            'player'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'day','date', 'created_at', 'updated_at','qr_url'], 'safe'],
            [['schedules_id', 'academy_id', 'subscription_id', 'player_id', 'academy_sport_id', 'created_by', 'updated_by','subscription_detail_id'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedules_player';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'day' => 'Day',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'date' => 'Date',
            'schedules_id' => 'Schedules ID',
            'academy_id' => 'Academy ID',
            'subscription_id' => 'Subscription ID',
            'player_id' => 'Player ID',
            'academy_sport_id' => 'Academy Sport ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(\common\models\Academies::className(), ['id' => 'academy_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySport()
    {
        return $this->hasOne(\common\models\AcademySport::className(), ['id' => 'academy_sport_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasOne(\common\models\Schedules::className(), ['id' => 'schedules_id']);
    }
    public function getSubscriptionPackage()
    {
        return $this->hasOne(SubscriptionDetails::class, ['player_id' => 'player_id']);
    }
    public function getSubscriptionStartDate()
    {
        return $this->hasOne(SubscriptionDetails::class, ['player_id' => 'player_id']);
    }
    public function getSubscriptionEndDate()
    {
        return $this->hasOne(SubscriptionDetails::class, ['player_id' => 'player_id']);
    }
    public function getSubscriptionStatus()
    {
        $subscription = Subscription::findOne($this->subscription_id);
        return $subscription ? $subscription->subscription_status : Yii::t('common', 'Unknown');
    }
    public function getSubscription()
{
    return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
}

public function getSubscriptionDetail()
{
    return $this->hasOne(SubscriptionDetails::class, [
        'subscription_id' => 'subscription_id',
        'player_id' => 'player_id',
        'sports_id' => 'academy_sport.sport_id',
    ])->via('academySport');
}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'player_id']);
    }


    public function getPlayerFirstName()
    {
        return $this->userProfile ? $this->userProfile->firstname : Yii::t('common', 'Unknown');
    }
    public function getPlayerLastName()
    {
        // Assuming 'subscriptionDetails' has a relation to 'Subscription' and 'Subscription' has 'parent_id'
        $subscription = $this->subscriptionDetails ? $this->subscriptionDetails->subscription : null;
        $parentUser = $subscription ? $subscription->parentUser : null; // Assuming 'parentUser' relationship is defined in 'Subscription' model
        $parentProfile = $parentUser ? $parentUser->userProfile : null; // Assuming 'userProfile' relation in 'User' model
    
        return $parentProfile ? $parentProfile->firstname : Yii::t('common', 'Unknown');
    }
    
    
    public function getUserProfile()
{
    return $this->hasOne(UserProfile::class, ['user_id' => 'player_id']);
}
/**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return SchedulesPlayerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchedulesPlayerQuery(get_called_class());
    }
    


 

public function getIsRegistered()
    {
        // Define logic for determining if the player is registered
        // For example, based on other attributes in the model
        return $this->_isRegistered;
    }

    public function setIsRegistered($value)
    {
        $this->_isRegistered = $value;
    }
    public static function hasSelectedSchedule($player_id, $sport_id, $subscription_id)
    {
        return self::find()
            ->joinWith('academySport')
            ->where(['player_id' => $player_id, 'academy_sport.sport_id' => $sport_id,'subscription_id' => $subscription_id])
            ->exists();
    }

    /**
 * Gets query for [[PlayerAttendances]].
 *
 * @return \yii\db\ActiveQuery
 */
public function getPlayerAttendances()
{
    return $this->hasMany(PlayerAttendance::class, ['schplayer_id' => 'id']);
}
  /**
 * Gets query for [[SubscriptionDetails]].
 *
 * @return \yii\db\ActiveQuery
 */
public function getSubscriptionDetails()
{
    return $this->hasOne(SubscriptionDetails::class, [
        'subscription_id' => 'subscription_id',
        'player_id' => 'player_id',
        'academy_sport_id' => 'academy_sport_id',
    ]);
}
public function getSubscriptionDetailss()
{
    return $this->hasOne(SubscriptionDetails::class, [
        'subscription_id' => 'subscription_id',
        'player_id' => 'player_id',
        //'academy_sport_id' => 'academy_sport_id',
        'sports_id' => 'academy_sport_id',

    ]);
}
public function getParent()
{
    return $this->hasOne(\common\models\User::class, ['id' => 'parent_id'])
                ->via('player'); 
}

public function getParentProfile()
{
    return $this->hasOne(UserProfile::class, ['user_id' => 'id'])
                ->via('parent'); 
}
public function getSubscriptionDetailsFlexible()
{
    return SubscriptionDetails::find()
        ->where(['player_id' => $this->player_id])
        ->andWhere(['sports_id' => $this->academySport->sport_id ?? 0])
        ->andWhere(['subscription_id' => $this->subscription_id])
        ->one();
}
public function getSubscriptionDetailById()
{
    return $this->hasOne(SubscriptionDetails::class, ['id' => 'subscription_detail_id']);
}


}
