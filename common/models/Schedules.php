<?php

namespace common\models;

use common\helpers\TimeHelper;
use Yii;

/**
 * This is the model class for table "schedules".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $gender
 * @property int[]|null $days
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int|null $level
 * @property int|null $capacity
 * @property int|null $current_capacity
 * @property int|null $remaining_capacity
 * @property int|null $academy_id
 * @property int|null $trainer_id
 * @property int|null $academy_sport_id
 * @property int|null $age_group_id
 * @property int|null $coach_id 
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $main_schedule_team_id
 *
 * @property Academies $academy
 * @property AcademySport $academySport
 * @property AgeGroup $ageGroup
 * @property User $trainer
 * @property CoachProfile $coach 
 */
class Schedules extends \yii\db\ActiveRecord
{
    public $days = [];


    public $close = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'gender', 'start_time', 'end_time', 'level', 'capacity', 'academy_id', 'coach_id', 'academy_sport_id', 'age_group_id'], 'required'],
            [['gender', 'level', 'capacity', 'current_capacity', 'remaining_capacity', 'academy_id', 'trainer_id', 'academy_sport_id', 'age_group_id', 'coach_id', 'created_by', 'updated_by', 'main_schedule_team_id'], 'integer'], // الحقل الجديد
            [['days'], 'safe'],
            [['start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['trainer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['trainer_id' => 'id']],
            [['academy_sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySport::class, 'targetAttribute' => ['academy_sport_id' => 'id']],
            [['age_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgeGroup::class, 'targetAttribute' => ['age_group_id' => 'id']],
            [['coach_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoachProfile::class, 'targetAttribute' => ['coach_id' => 'id']], // القاعدة الجديدة
            [['main_schedule_team_id'], 'exist', 'skipOnError' => true, 'targetClass' => MainScheduleTeams::class, 'targetAttribute' => ['main_schedule_team_id' => 'id']],
            ['start_time', 'validateAcademyTime'],
            ['end_time', 'validateAcademyTime'],



        ];
    }
    public function validateAcademyTime($attribute, $params)
    {
        $academy = $this->getAcademy()->one();

        if ($academy) {
            $academyStartTime = strtotime($academy->startTime);
            $academyEndTime = strtotime($academy->endTime);
            $inputTime = strtotime($this->$attribute);

            if ($inputTime < $academyStartTime || $inputTime > $academyEndTime) {
                $this->addError($attribute, Yii::t('common', 'The time must be between the academy\'s working hours: {startTime} and {endTime}', [
                    'startTime' => $academy->startTime,
                    'endTime' => $academy->endTime,
                ]));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'gender' => Yii::t('common', 'Gender'),
            'days' => Yii::t('common', 'Days'),
            'day' => Yii::t('common', 'Day'),
            'start_time' => Yii::t('common', 'Start Time'),
            'end_time' => Yii::t('common', 'End Time'),
            'level' => Yii::t('common', 'Level'),
            'capacity' => Yii::t('common', 'Capacity'),
            'current_capacity' => Yii::t('common', 'Current Capacity'),
            'remaining_capacity' => Yii::t('common', 'Remaining Capacity'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'trainer_id' => Yii::t('common', 'Trainer ID'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
            'age_group_id' => Yii::t('common', 'Age Group ID'),
            'coach_id' => Yii::t('common', 'Coach ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    // gender options
    public static function getGenderOptions()
    {
        return [
            1 => Yii::t('common', 'Girls'),
            2 => Yii::t('common', 'Boys'),
            3 => Yii::t('common', 'Mixed'),
        ];
    }

    // level options
    public static function getLevelOptions()
    {
        return [
            1 => Yii::t('common', 'Beginner'),
            2 => Yii::t('common', 'Intermediate'),
            3 => Yii::t('common', 'Advanced'),
        ];
    }


    public static function findUpcoming($trainerId)
    {
        $currentDay = (int) date('w');
        if ($currentDay == 0) {
            $currentDay = 7;
        }
        $currentTime = date('H:i:s');

        return self::find()
            ->where(['trainer_id' => $trainerId])
            ->andWhere([
                'OR',
                [
                    'AND',
                    ['day' => $currentDay],
                    ['>=', 'start_time', $currentTime],
                ],
                ['>', 'day', $currentDay],
            ])
            ->orderBy(['day' => SORT_ASC, 'start_time' => SORT_ASC])
            ->limit(7)
            ->all();
    }

    // Days options
    public static function getDayOptions()
    {
        return [
            7 => Yii::t('common', 'Sunday'),
            1 => Yii::t('common', 'Monday'),
            2 => Yii::t('common', 'Tuesday'),
            3 => Yii::t('common', 'Wednesday'),
            4 => Yii::t('common', 'Thursday'),
            5 => Yii::t('common', 'Friday'),
            6 => Yii::t('common', 'Saturday'),
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
     * Gets query for [[AgeGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgeGroup()
    {
        return $this->hasOne(AgeGroup::class, ['id' => 'age_group_id']);
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

    /**
     * Gets query for [[CoachProfile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoach()
    {
        return $this->hasOne(CoachProfile::class, ['id' => 'coach_id']); // العلاقة الجديدة
    }

    /**
     * Load all attributes from the given data array.
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Save all attributes and handle conflicts.
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        // Handle Update
        if (!$this->isNewRecord) {
            // Ensure days is an array
            if (!empty($this->days)) {
                $this->days = is_array($this->days) ? $this->days : explode(',', $this->days);
                $this->day = reset($this->days);
            } else {
                $this->day = $this->getOldAttribute('day');
            }

            // Format start and end times
            if (!empty($this->start_time)) {
                $this->start_time = date("H:i:s", strtotime($this->start_time));
            }
            if (!empty($this->end_time)) {
                $this->end_time = date("H:i:s", strtotime($this->end_time));
            }

            if (!empty($this->coach_id)) {
                $coachProfile = CoachProfile::findOne($this->coach_id);
                if ($coachProfile) {
                    $this->trainer_id = $coachProfile->user_id;
                }
            }

            // Check for schedule conflict based on coach_id
            // if ($this->isScheduleConflict()) {
            //     $this->addError('coach_id', Yii::t('common', 'The coach is already scheduled for another session at the same time.'));
            //     return false;
            // }

            return $this->save($runValidation, $attributeNames);
        }
        // Handle Insert
        else {
            // Initialize an array to store the IDs of newly created records
            $newRecordIds = [];

            // Ensure days is an array
            if (!empty($this->days)) {
                $this->days = is_array($this->days) ? $this->days : explode(',', $this->days);

                foreach ($this->days as $day) {
                    $model = new Schedules();
                    $model->attributes = $this->attributes;
                    $model->day = $day;

                    // Format start and end times
                    if (!empty($model->start_time)) {
                        $model->start_time = date("H:i:s", strtotime($model->start_time));
                    }
                    if (!empty($model->end_time)) {
                        $model->end_time = date("H:i:s", strtotime($model->end_time));
                    }

                    if (!empty($model->coach_id)) {
                        $coachProfile = CoachProfile::findOne($model->coach_id);
                        if ($coachProfile) {
                            $model->trainer_id = $coachProfile->user_id;
                        }
                    }

                    // Check for schedule conflict based on coach_id
                    // if ($model->isScheduleConflict()) {
                    //     $this->addError('coach_id', Yii::t('common', 'The coach is already scheduled for another session at the same time.'));
                    //     return false;
                    // }

                    // Save the model
                    if (!$model->save($runValidation, $attributeNames)) {
                        return false;
                    }

                    $newRecordIds[] = $model->id;
                }
                // return true;
                return [true, $newRecordIds];
            }
            return false;
        }
    }




    /**
     * Check if there is a scheduling conflict for the trainer.
     * @return bool
     */
    private function isScheduleConflict()
    {
        $conflict = Schedules::find()
            ->where([
                'coach_id' => $this->coach_id,
                'day' => $this->day,
                'academy_sport_id' => $this->academy_sport_id,
            ])
            ->andWhere([
                'OR',
                [
                    'AND',
                    ['<=', 'start_time', $this->start_time],
                    ['>=', 'end_time', $this->start_time]
                ],
                [
                    'AND',
                    ['<=', 'start_time', $this->end_time],
                    ['>=', 'end_time', $this->end_time]
                ],
                [
                    'AND',
                    ['>=', 'start_time', $this->start_time],
                    ['<=', 'end_time', $this->end_time]
                ]
            ])
            ->andWhere(['!=', 'id', $this->id])
            ->exists();

        return $conflict;
    }


    /**
     * Deletes the model and related models if any.
     * @return bool Whether the deletion was successful.
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Store the main_schedule_team_id before deleting this schedule
            $mainScheduleTeamId = $this->main_schedule_team_id;

            // Delete this schedule
            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            // Check if there are any remaining schedules with this main_schedule_team_id
            $count = (new \yii\db\Query())
                ->from('schedules')
                ->where(['main_schedule_team_id' => $mainScheduleTeamId])
                ->count();

            // Only delete from main_schedule_teams if there are no related schedules left
            if ($count == 0 && $mainScheduleTeamId !== null) {
                \Yii::$app->db->createCommand()
                    ->delete('main_schedule_teams', ['id' => $mainScheduleTeamId])
                    ->execute();
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Get the players associated with this schedule.
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(UserProfile::class, ['user_id' => 'player_id'])
            ->viaTable('subscription_details', ['packages_id' => 'academy_sport_id']);
    }

    public function getSchedulesPlayers()
    {
        return $this->hasMany(SchedulesPlayer::className(), ['schedules_id' => 'id']);
    }

    public function getSchedulesPlayersperDay()
    {
        $dayDate = TimeHelper::getDateOfDayOfCurrentWeek($this->day);
        //echo $dayDate .'-----------------------';die;
        //return $this->hasMany(SchedulesPlayer::className(), ['schedules_id' => 'id' ,'day'=>'day'])->where(['date'=>$dayDate]);
        return $this->hasMany(SchedulesPlayer::className(), ['schedules_id' => 'id', 'day' => 'day'])
            ->where(['DATE(date)' => $dayDate]);
    }

    public function getSchedulesPlayersperRequiredDay($dayDate)
    {
        //return $this->hasMany(SchedulesPlayer::className(), ['schedules_id' => 'id' ,'day'=>'day'])->where(['date'=>$dayDate]);
        return $this->hasMany(SchedulesPlayer::className(), ['schedules_id' => 'id', 'day' => 'day'])
            ->where(['DATE(date)' => $dayDate])->all();
    }
    public function getCoachName()
    {
        $coach = $this->getCoach()->one();
        return $coach ? $coach->name : 'unknow';
    }


    public function getCurrentCapacity()
    {
        return $this->getSchedulesPlayers()
            ->select('player_id')
            ->groupBy('player_id')
            ->count();
    }


    public function getRemainingCapacity()
    {
        return $this->capacity - $this->getCurrentCapacity();
    }


    public function getCapacityOverview()
    {
        return $this->getCurrentCapacity() . '/' . $this->capacity;
    }

    public function getCoachProfile()
    {
        return $this->hasOne(CoachProfile::class, ['id' => 'coach_id']);
    }

    public function getCoachFirstName()
    {
        $coachProfile = $this->getCoachProfile()->one();
        return $coachProfile ? $coachProfile->getUserProfile()->one()->firstname ?? 'Unknown' : 'Unknown';
    }

    public function getCoachFirstNameAndUserId()
    {
        $coachProfile = $this->getCoachProfile()->one();
        return [
            'firstName' => $coachProfile->userProfile->firstname ?? 'Unknown',
            'userId' => $coachProfile->userProfile->user_id ?? 'Unknown'
        ];
    }

    public function getMainScheduleTeam()
    {
        return $this->hasOne(MainScheduleTeams::class, ['id' => 'main_schedule_team_id']);
    }

    public function getPlayerCountByCurrentWeek($scheduleId)
    {
        $startOfWeek = date('Y-m-d', strtotime('last Sunday'));
        $endOfWeek = date('Y-m-d', strtotime('next Saturday'));

        return SchedulesPlayer::find()
            ->where(['schedules_id' => $scheduleId])
            ->andWhere(['between', 'date', $startOfWeek, $endOfWeek])
            ->count();
    }

    /**
     *
     * @param int $newDay 
     * @param int $originalDay
     * @param string $originalDate 
     * @return string 
     */
    public function calculateNewDate($newDay, $originalDay, $originalDate)
    {
        $dayDifference = $newDay - $originalDay;

        $date = new \DateTime($originalDate);

        if ($dayDifference !== 0) {
            $date->modify("$dayDifference days");
        }

        return $date->format('Y-m-d');
    }

    public function getPlayerCountByDate($scheduleId, $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        return SchedulesPlayer::find()
            ->where(['schedules_id' => $scheduleId])
            ->andWhere(['DATE(date)' => $date])
            ->count();
    }

    public function getRegisteredPlayersQuery($from = null, $to = null)
    {
        $query = \common\models\SchedulesPlayer::find()->where(['schedules_id' => $this->id]);
        if ($from && $to) {
            $query->andWhere(['between', 'DATE(date)', $from, $to]);
        }
        return $query;
    }
}
