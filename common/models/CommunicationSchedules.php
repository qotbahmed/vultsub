<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "communication_schedules".
 *
 * @property int $id
 * @property int|null $communication_id
 * @property string|null $schedules_id
 * @property int|null $day_of_week
 * @property int|null $academy_sport_id
 *
 * @property AcademySport $academySport
 * @property Communication $communication
 */
class CommunicationSchedules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'communication_schedules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['communication_id', 'day_of_week', 'academy_sport_id'], 'integer'],
            [['schedules_id'], 'string', 'max' => 255],
            [['communication_id'], 'exist', 'skipOnError' => true, 'targetClass' => Communication::class, 'targetAttribute' => ['communication_id' => 'id']],
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
            'communication_id' => Yii::t('common', 'Communication ID'),
            'schedules_id' => Yii::t('common', 'Schedules ID'),
            'day_of_week' => Yii::t('common', 'Day Of Week'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
        ];
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
     * Gets query for [[Communication]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunication()
    {
        return $this->hasOne(Communication::class, ['id' => 'communication_id']);
    }
}
