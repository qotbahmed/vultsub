<?php

namespace common\models;

use Yii;
use common\models\User;
use common\models\Academies;

/**
 * This is the model class for table "coach_attendance".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $day
 * @property string|null $date
 * @property string|null $attendance
 * @property string|null $departure
 *
 * @property User $user
 */
class CoachAttendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coach_attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'day'], 'integer'],
            [['date', 'attendance', 'departure'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

            [['user_id', 'attendance'], 'required'],
            [['attendance', 'departure'], 'validateTimeOrder'],
            ['attendance', 'validateAttendanceTimes'],
            ['departure', 'validateDepartureTimes'],
            // ['day', 'validateDay'],
            ['date', 'validateDate'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('backend', 'Trainer'),
            'day' => Yii::t('backend', 'Day'),
            'date' => Yii::t('backend', 'date'),
            'attendance' => Yii::t('backend', 'Attendance'),
            'departure' => Yii::t('backend', 'Departure'),
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

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }

    public function validateTimeOrder($attribute, $params)
    {
        // Ensure 'attendance' is not after or equal to 'departure' if both are provided
        if ($this->attendance && $this->departure) {
            $attendanceTime = strtotime($this->attendance);
            $departureTime = strtotime($this->departure);
            
            if ($attendanceTime > $departureTime) {
                $this->addError($attribute, Yii::t('backend', 'Attendance time cannot be after Departure time.'));
            } elseif ($attendanceTime === $departureTime) {
                $this->addError($attribute, Yii::t('backend', 'Attendance time cannot be the same as Departure time.'));
            }
        }
    }

    public function validateAttendanceTimes($attribute, $params)
    {
        $this->validateTime($attribute, 'attendance');
    }

    public function validateDepartureTimes($attribute, $params)
    {
        $this->validateTime($attribute, 'departure');
    }

    private function validateTime($attribute, $timeAttribute)
    {
        $academy = $this->getAcademyByUser();
        
        if ($academy && $this->$timeAttribute) {
            $time = strtotime($this->$timeAttribute);

            // Check if time falls outside academy working hours
            if ($time < strtotime($academy->startTime) || $time > strtotime($academy->endTime)) {
                $this->addError($attribute, Yii::t('backend', '{timeAttribute} time must be within the Academy\'s working hours ({startTime} - {endTime})', [
                    'timeAttribute' => Yii::t('backend', ucfirst($timeAttribute)),
                    'startTime' => $academy->startTime,
                    'endTime' => $academy->endTime,
                ]));
            }
        }
    }

    public function validateDate($attribute, $params)
    {
        if (!$this->user_id || !$this->date) {
            return;
        }

        // Extract day of the week from the selected date
        $dayOfWeekFromDate = date('w', strtotime($this->date));

        // Retrieve academy's working days
        $academy = $this->getAcademyByUser();
        if (!$academy || !$this->isDayWithinWorkingDays($dayOfWeekFromDate, $academy)) {
            $this->addError($attribute, Yii::t('backend', 'The selected date is not within the Academy\'s working days.'));
        }
    }

    private function getAcademyByUser()
    {
        $userProfile = UserProfile::findOne(['user_id' => $this->user_id]);
        return $userProfile ? Academies::findOne($userProfile->academy_id) : null;
    }

    private function isDayWithinWorkingDays($dayOfWeek, $academy)
    {
        $workingDays = $academy->getWorkingDays();
        return $workingDays && in_array($dayOfWeek, $workingDays);
    }


}
