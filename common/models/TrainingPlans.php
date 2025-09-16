<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "training_plans".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $main_schedule_team_id
 * @property int|null $academy_id
 *
 * @property MainScheduleTeams $mainScheduleTeam
 * @property TrainingPlansSessions[] $trainingPlansSessions
 */
class TrainingPlans extends \yii\db\ActiveRecord
{
    public $main_schedule_team_ids;
    public $close;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'training_plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['main_schedule_team_id','academy_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['main_schedule_team_id'], 'exist', 'skipOnError' => true, 'targetClass' => MainScheduleTeams::class, 'targetAttribute' => ['main_schedule_team_id' => 'id']],
            [['main_schedule_team_ids'], 'each', 'rule' => ['integer']],
            [['main_schedule_team_id', 'title'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'main_schedule_team_id' => Yii::t('common', 'Main Schedule Team ID'),
            'main_schedule_team_ids' => Yii::t('common', 'Teams'),
            'academy_id' => Yii::t('common', 'Academy ID'),
        ];
    }

    /**
     * Gets query for [[MainScheduleTeam]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMainScheduleTeam()
    {
        return $this->hasOne(MainScheduleTeams::class, ['id' => 'main_schedule_team_id']);
    }

    /**
     * Gets query for [[TrainingPlansSessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingPlansSessions()
    {
        return $this->hasMany(TrainingPlansSessions::class, ['training_plans_id' => 'id']);
    }
}
