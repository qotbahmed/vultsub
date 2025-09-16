<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vital_sign".
 *
 * @property int $id
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $medical_history_id
 * @property float|null $Weight
 * @property float|null $height
 * @property float|null $bmi
 * @property int|null $age
 * @property string|null $date_of_birth
 * @property int|null $gender
 * @property int|null $body_condition
 * @property int $player_id
 * @property int|null $notification_preference
 * @property int|null $assignment_status
 * @property string|null $start_date
 * @property string|null $end_date
 *
 * @property PlayerAnswerMedical $medicalHistory
 * @property User $player
 */
class VitalSign extends \yii\db\ActiveRecord
{
    public $user_type;
    public $academy_id;

        const CONDITION_UNDERWEIGHT = '1';
        const CONDITION_NORMAL = '0';
        const CONDITION_OVERWEIGHT = '2';
        const CONDITION_OBESITY = '3';

        const STATUS_PENDING = 0;
        const STATUS_COMPLETED = 1;
        const STATUS_FINISHED = 2; 
        const STATUS_NEAR_EXPIRY = 3;  
    

        const GENDER_MALE = 1;
        const GENDER_FEMALE = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vital_sign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at', 'date_of_birth'], 'safe'],
            [['created_by', 'updated_by', 'medical_history_id', 'age', 'gender', 'player_id'], 'integer'],
            [['Weight', 'height'], 'number'],
            [['player_id','height','Weight','gender','date_of_birth'], 'required'],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
            [['medical_history_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayerAnswerMedical::class, 'targetAttribute' => ['medical_history_id' => 'id']],
            [['date_of_birth'], 'date', 'format' => 'php:Y-m-d'],
            ['gender', 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]], // Validate gender
            ['height', 'number', 'max' => 220], // Maximum height 220
            ['Weight', 'number', 'max' => 300], // Maximum weight 300
            ['notification_preference', 'in', 'range' => [0, 1]],
            ['assignment_status', 'in', 'range' => [0, 1]],
            ['start_date', 'date', 'format' => 'php:Y-m-d'],
            ['end_date', 'date', 'format' => 'php:Y-m-d'],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_at' => Yii::t('common', 'Created At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'medical_history_id' => Yii::t('common', 'Medical History ID'),
            'Weight' => Yii::t('common', 'Weight'),
            'height' => Yii::t('common', 'Height'),
            'bmi' => Yii::t('common', 'Bmi'),
            'age' => Yii::t('common', 'Age'),
            'date_of_birth' => Yii::t('common', 'Date Of Birth'),
            'gender' => Yii::t('common', 'Gender'),
            'body_condition' => Yii::t('common', 'Body Condition'),
            'player_id' => Yii::t('common', 'Player ID'),
            'notification_preference' => Yii::t('common', 'Notification Preference'),
            'assignment_status' => Yii::t('common', 'Assignment Status'),
            'start_date' => Yii::t('common', 'Start Date'),
            'end_date' => Yii::t('common', 'End Date'),
        ];
    }


    public function validateBirthdate($attribute, $params)
{
    $birthDate = new \DateTime($this->$attribute);
    $minDate = new \DateTime('1920-01-01');
    $currentDate = new \DateTime();

    if ($birthDate < $minDate) {
        $this->addError($attribute, Yii::t('common', 'Birthdate cannot be earlier than 1920.'));
    }

    if ($birthDate > $currentDate) {
        $this->addError($attribute, Yii::t('common', 'Birthdate cannot be in the future.'));
    }
}

    /**
     * Gets query for [[playerAnswerMedical]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getplayerAnswerMedical()
    {
        return $this->hasOne(PlayerAnswerMedical::class, ['id' => 'medical_history_id']);
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'player_id']);
    }
    public function getProfile()
{
    return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
}




public function getUserProfile()
{
    return $this->hasOne(UserProfile::class, ['user_id' => 'player_id']);
}

      /**
     * Loads the model with data.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model with optional validation and attribute selection.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     * 
     * @return bool Whether the deletion was successful.
     * @throws \Exception
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add additional deletion logic here, if necessary.

            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->Weight && $this->height) {
                $heightInMeters = $this->height / 100; 
                $this->bmi = $this->Weight / ($heightInMeters * $heightInMeters); 

                if ($this->bmi < 18.5) {
                    $this->body_condition = self::CONDITION_UNDERWEIGHT;
                } elseif ($this->bmi >= 18.5 && $this->bmi < 24.9) {
                    $this->body_condition = self::CONDITION_NORMAL;
                } elseif ($this->bmi >= 25 && $this->bmi < 29.9) {
                    $this->body_condition = self::CONDITION_OVERWEIGHT;
                } else {
                    $this->body_condition = self::CONDITION_OBESITY;
                }
            }

            return true;
        }

        return false;
    }
    public function getBodyConditionText()
{
    switch ($this->body_condition) {
        case self::CONDITION_UNDERWEIGHT:
            return Yii::t('common', 'Underweight');
        case self::CONDITION_NORMAL:
            return Yii::t('common', 'Normal');
        case self::CONDITION_OVERWEIGHT:
            return Yii::t('common', 'Overweight');
        case self::CONDITION_OBESITY:
            return Yii::t('common', 'Obesity');
        default:
            return Yii::t('common', 'Unknown');
    }
}

public function getAgeFromBirthdate()
{
    if ($this->date_of_birth) {
        $birthDate = new \DateTime($this->date_of_birth);
        $currentDate = new \DateTime();
        $ageInterval = $currentDate->diff($birthDate);

        return "{$ageInterval->y} سنة، {$ageInterval->m} شهر، {$ageInterval->d} يوم";
    }
    return null; 
}

public function getGenderText()
{
    switch ($this->gender) {
        case self::GENDER_MALE:
            return Yii::t('common', 'Male');
        case self::GENDER_FEMALE:
            return Yii::t('common', 'Female');
        default:
            return Yii::t('common', 'Unknown');
    }
}



public function getVitalLogs()
{
    return $this->hasMany(VitalLog::class, ['vital_sign_id' => 'id']);
}
public static function getLatestVitalSign($playerId)
{
    return self::find()
        ->where(['player_id' => $playerId])
        ->orderBy(['created_at' => SORT_DESC])
        ->one();
}
public function getEvaluationStatus()
{
    $today = new DateTime();
    $endDate = new DateTime($this->end_date);
    $daysRemaining = $endDate->diff($today)->days;

    if ($endDate < $today) {
        return self::STATUS_FINISHED;
    } elseif ($daysRemaining <= 7 && $daysRemaining > 0) {
        return self::STATUS_NEAR_EXPIRY;
    } else {
        return self::STATUS_PENDING;
    }
}
  /**
     * Get text representation of the evaluation status.
     *
     * @param int $status
     * @return string
     */

    public static function getStatusText($status)
{
    $statuses = [
        self::STATUS_PENDING => Yii::t('common', 'Pending'),
        self::STATUS_COMPLETED => Yii::t('common', 'Completed'),
        self::STATUS_FINISHED => Yii::t('common', 'Finished'),
        self::STATUS_NEAR_EXPIRY => Yii::t('common', 'Near Expiry (7 days left)'),
    ];

    return $statuses[$status] ?? Yii::t('common', 'Unknown');
}

}
