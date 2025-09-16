<?php

namespace common\models;

use DateTime;

use Yii;

/**
 * This is the model class for table "vital_log".
 *
 * @property int $id
 * @property int $vital_sign_id
 * @property int|null $notification_preference
 * @property int|null $assignment_status
 * @property string $start_date
 * @property string $end_date
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $created_by
 *
 * @property VitalSign $vitalSign
 */
class VitalLog extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_FINISHED = 2; 
    const STATUS_NEAR_EXPIRY = 3;  


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vital_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vital_sign_id', 'start_date', 'end_date'], 'required'],
            [['vital_sign_id', 'notification_preference', 'assignment_status', 'updated_by', 'created_by'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['vital_sign_id'], 'exist', 'skipOnError' => true, 'targetClass' => VitalSign::class, 'targetAttribute' => ['vital_sign_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'vital_sign_id' => Yii::t('common', 'Vital Sign ID'),
            'notification_preference' => Yii::t('common', 'Notification Preference'),
            'assignment_status' => Yii::t('common', 'Assignment Status'),
            'start_date' => Yii::t('common', 'Start Date'),
            'end_date' => Yii::t('common', 'End Date'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_by' => Yii::t('common', 'Created By'),
        ];
    }

    /**
     * Gets query for [[VitalSign]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVitalSign()
    {
        return $this->hasOne(VitalSign::class, ['id' => 'vital_sign_id']);
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
     * Get the evaluation status based on current date.
     *
     * @return int
     */
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
