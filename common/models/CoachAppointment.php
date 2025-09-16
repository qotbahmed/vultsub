<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coach_appointment".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $day
 * @property string|null $start_time
 * @property string|null $end_time
 *
 * @property User $user
 */
class CoachAppointment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coach_appointment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'day'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'day' => Yii::t('common', 'Day'),
            'start_time' => Yii::t('common', 'Start Time'),
            'end_time' => Yii::t('common', 'End Time'),
        ];
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
}
