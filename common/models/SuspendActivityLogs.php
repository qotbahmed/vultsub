<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "suspend_activity_logs".
 *
 * @property int $id
 * @property int|null $player_id
 * @property int|null $subscription_details_id
 * @property string|null $suspend_comment
 * @property string|null $from_date
 * @property string|null $to_date
 * @property int|null $is_suspended
 * @property string|null $created_at
 * @property int|null $suspended_classess
 * @property int $sports_id

 *
 * @property User $player
 * @property SubscriptionDetails $subscriptionDetails
 * @property Sport $sports

 */
class SuspendActivityLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suspend_activity_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['player_id', 'subscription_details_id', 'is_suspended', 'suspended_classess','sports_id'], 'integer'],
            [['suspend_comment'], 'string'],
            [['from_date', 'to_date', 'created_at'], 'safe'],
            [['subscription_details_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubscriptionDetails::class, 'targetAttribute' => ['subscription_details_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
            [['sports_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sports_id' => 'id']],   

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'player_id' => Yii::t('common', 'Player ID'),
            'subscription_details_id' => Yii::t('common', 'Subscription Details ID'),
            'suspend_comment' => Yii::t('common', 'Suspend Comment'),
            'from_date' => Yii::t('common', 'From Date'),
            'to_date' => Yii::t('common', 'To Date'),
            'is_suspended' => Yii::t('common', 'Is Suspended'),
            'created_at' => Yii::t('common', 'Created At'),
            'suspended_classess' => Yii::t('common', 'Suspended Classess'),
            'sports_id' => Yii::t('common', 'Sports ID'),

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
     * Gets query for [[SubscriptionDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionDetails()
    {
        return $this->hasOne(SubscriptionDetails::class, ['id' => 'subscription_details_id']);
    }

    public function getSports()
{
    return $this->hasOne(Sport::class, ['id' => 'sports_id']);
}

}
