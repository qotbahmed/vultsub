<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coach_profile".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $qualification
 * @property string|null $academic_achievements
 * @property string|null $personal_achievements
 * @property int|null $reward 1->Fixed, 2->Percentage, 3->Hourly
 * @property float|null $reward_value
 * @property int|null $sport_id
 *
 * @property Sport $sport
 * @property User $user
 */
class CoachProfile extends \yii\db\ActiveRecord
{
    const TRAINER_REWARD_FIXED_RATE = 1;
    const TRAINER_REWARD_PERCENTAGE = 2;
    const TRAINER_REWARD_HOURLY = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coach_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'reward', 'sport_id'], 'integer'],
            [['qualification', 'academic_achievements', 'personal_achievements'], 'string'],
            [['reward_value'], 'number'],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sport_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['sport_id', 'reward'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'qualification' => Yii::t('backend', 'Qualification'),
            'academic_achievements' => Yii::t('backend', 'Academic Achievements'),
            'personal_achievements' => Yii::t('backend', 'Personal Achievements'),
            'reward' => Yii::t('backend', 'How to calculate reward'),
            'reward_value' => Yii::t('backend', 'Reward'),
            'sport_id' => Yii::t('backend', 'sports'),
        ];
    }

    /**
     * Gets query for [[Sport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sport::class, ['id' => 'sport_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']); // Adjust 'user_id' to the actual key
    }

    public static function getRewardTypes()
    {
        return [
            self::TRAINER_REWARD_FIXED_RATE => Yii::t('backend', 'Fixed Rate'),
            self::TRAINER_REWARD_PERCENTAGE => Yii::t('backend', 'Percentage'),
            self::TRAINER_REWARD_HOURLY => Yii::t('backend', 'Hourly'),
        ];
    }

    public function getRewardTypeLabel()
    {
        $rewardTypes = self::getRewardTypes();
        return isset($rewardTypes[$this->reward]) ? $rewardTypes[$this->reward] : Yii::t('backend', 'Unknown');
    }

    public static function getRewardUnits()
    {
        return [
            self::TRAINER_REWARD_FIXED_RATE => '',
            self::TRAINER_REWARD_PERCENTAGE => '%',
            self::TRAINER_REWARD_HOURLY => 'hr/',
        ];
    }
}
