<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "training_plans_sessions".
 *
 * @property int $id
 * @property int|null $session_number
 * @property string|null $notes
 * @property int $training_plans_id
 *
 * @property TrainingPlans $trainingPlans
 */
class TrainingPlansSessions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'training_plans_sessions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['session_number', 'training_plans_id'], 'integer'],
            [['notes'], 'string'],
            [['training_plans_id'], 'required'],
            [['training_plans_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrainingPlans::class, 'targetAttribute' => ['training_plans_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'session_number' => Yii::t('common', 'Session Number'),
            'notes' => Yii::t('common', 'Notes'),
            'training_plans_id' => Yii::t('common', 'Training Plans ID'),
        ];
    }

    /**
     * Gets query for [[TrainingPlans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingPlans()
    {
        return $this->hasOne(TrainingPlans::class, ['id' => 'training_plans_id']);
    }
}
