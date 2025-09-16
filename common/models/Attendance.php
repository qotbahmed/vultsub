<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attendance".
 *
 * @property int $id
 * @property int $schedule_id
 * @property int $user_id
 * @property int|null $attendance_status
 * @property string|null $created_at
 */
class Attendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['schedule_id', 'user_id'], 'required'],
            [['schedule_id', 'user_id', 'attendance_status'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'schedule_id' => Yii::t('common', 'Schedule ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'attendance_status' => Yii::t('common', 'Attendance Status'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }
}
