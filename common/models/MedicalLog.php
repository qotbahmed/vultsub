<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "medical_log".
 *
 * @property int $id
 * @property int $player_answer_medical_id
 * @property int|null $notification_preference
 * @property int|null $assignment_status
 * @property string $start_date
 * @property string $end_date
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $created_by
 *
 * @property PlayerAnswerMedical $playerAnswerMedical
 */
class MedicalLog extends \yii\db\ActiveRecord
{
    const STATUS_EVALUATED = 0;  
    const STATUS_NOT_EVALUATED = 1; 
    const STATUS_EXPIRED = 2;  
    const STATUS_NEAR_EXPIRY = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medical_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['player_answer_medical_id', 'start_date', 'end_date'], 'required'],
            [['player_answer_medical_id', 'notification_preference', 'assignment_status', 'updated_by', 'created_by'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['player_answer_medical_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayerAnswerMedical::class, 'targetAttribute' => ['player_answer_medical_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'player_answer_medical_id' => Yii::t('common', 'Player Answer Medical ID'),
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
     * Gets query for [[PlayerAnswerMedical]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerAnswerMedical()
    {
        return $this->hasOne(PlayerAnswerMedical::class, ['id' => 'player_answer_medical_id']);
    }
    public function getEvaluationStatus()
    {
        $today = new \DateTime();
        $endDate = new \DateTime($this->end_date);
        $daysRemaining = $endDate->diff($today)->days;
        $isExpired = $endDate < $today;

        if ($isExpired) {
            return self::STATUS_EXPIRED;
        } elseif ($daysRemaining <= 7 && $daysRemaining > 0) {
            return self::STATUS_NEAR_EXPIRY;
        } elseif (!$isExpired && $this->end_date !== null) {
            return self::STATUS_EVALUATED;
        } else {
            return self::STATUS_NOT_EVALUATED;
        }
    }

    public static function getStatusText($status)
    {
        $statuses = [
            self::STATUS_EVALUATED => Yii::t('common', 'Evaluated'),
            self::STATUS_NOT_EVALUATED => Yii::t('common', 'Not Evaluated'),
            self::STATUS_EXPIRED => Yii::t('common', 'Expired'),
            self::STATUS_NEAR_EXPIRY => Yii::t('common', 'Near Expiry (7 days left)'),
        ];

        return isset($statuses[$status]) ? $statuses[$status] : Yii::t('common', 'Unknown');
    }
}
