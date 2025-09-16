<?php

namespace common\models;

use common\models\Schedules;
use Yii;

/**
 * This is the model class for table "main_schedule_teams".
 *
 * @property int $id
 * @property string $slug
 * @property string|null $team_name
 * @property int|null $day
 * @property string|null $start_time
 * @property int|null $sport_id
 * @property int|null $trainer_id
 * @property int|null $academy_id
 *
 * @property Academies $academy
 * @property Sport $sport
 * @property User $trainer
 */
class MainScheduleTeams extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'main_schedule_teams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug'], 'required'],
            [['day', 'sport_id', 'trainer_id', 'academy_id'], 'integer'],
            [['start_time'], 'safe'],
            [['slug', 'team_name'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sport_id' => 'id']],
            [['trainer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['trainer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'slug' => Yii::t('common', 'Slug'),
            'team_name' => Yii::t('common', 'Team Name'),
            'day' => Yii::t('common', 'Day'),
            'start_time' => Yii::t('common', 'Start Time'),
            'sport_id' => Yii::t('common', 'Sport ID'),
            'trainer_id' => Yii::t('common', 'Trainer ID'),
            'academy_id' => Yii::t('common', 'Academy ID'),
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
     * Gets query for [[Sport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sport::class, ['id' => 'sport_id']);
    }

    /**
     * Gets query for [[Trainer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrainer()
    {
        return $this->hasOne(User::class, ['id' => 'trainer_id']);
    }

    public function getSchedules()
    {
        return $this->hasMany(Schedules::class, ['main_schedule_team_id' => 'id']);
    }

    public function getTrainerFirstName()
    {
        return $this->trainer ? $this->trainer->userProfile->firstname : null;
    }

    public function getTeamName()
    {
        return !empty($this->team_name) ? $this->team_name : $this->slug;
    }

    public function getSports()
    {
        return $this->hasMany(Sport::class, ['id' => 'sport_id'])
            ->viaTable('academy_sport', ['academy_id' => 'id']);
    }

    public static function getTeamNamesByAcademy($academyId)
    {
        $isMainAcademy = Yii::$app->controller->MainAcadmin;
    
        // teams based on academy_id
        $query = self::find()->where(['academy_id' => $academyId]);
    
        if ($isMainAcademy) {
            $subAcademyIds = (new \yii\db\Query())
                ->select('id')
                ->from('academies')
                ->where(['parent_id' => $academyId])
                ->column();
    
            // teams for all sub-academies in the query
            $query->orWhere(['academy_id' => $subAcademyIds]);
        }
    
        // Execute the query and retrieve teams
        $teams = $query->all();
    
        return \yii\helpers\ArrayHelper::map($teams, 'slug', function($team) {
            return $team->getTeamName();
        });
    }    

}
