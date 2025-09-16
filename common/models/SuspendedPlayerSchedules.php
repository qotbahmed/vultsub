<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "suspended_player_schedules".
 *
 * @property int $id
 * @property int|null $day
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $date
 * @property int|null $schedules_id
 * @property int|null $academy_id
 * @property int|null $subscription_id
 * @property int|null $player_id
 * @property int|null $academy_sport_id
 * @property string|null $created_at
 *
 * @property Academies $academy
 * @property AcademySport $academySport
 * @property User $player
 * @property Schedules $schedules
 */
class SuspendedPlayerSchedules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suspended_player_schedules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['day', 'schedules_id', 'academy_id', 'subscription_id', 'player_id', 'academy_sport_id'], 'integer'],
            [['start_time', 'end_time', 'date', 'created_at'], 'safe'],
            [['schedules_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schedules::class, 'targetAttribute' => ['schedules_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
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
            'day' => Yii::t('common', 'Day'),
            'start_time' => Yii::t('common', 'Start Time'),
            'end_time' => Yii::t('common', 'End Time'),
            'date' => Yii::t('common', 'Date'),
            'schedules_id' => Yii::t('common', 'Schedules ID'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'subscription_id' => Yii::t('common', 'Subscription ID'),
            'player_id' => Yii::t('common', 'Player ID'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    /**
     * Gets query for [[AcademySport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySport()
    {
        return $this->hasOne(AcademySport::class, ['id' => 'academy_sport_id']);
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
     * Gets query for [[Schedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasOne(Schedules::class, ['id' => 'schedules_id']);
    }
}
