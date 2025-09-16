<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "communication".
 *
 * @property int $id
 * @property int|null $communication_type
 * @property string|null $message_text
 * @property int|null $send_method
 * @property int|null $academy_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Academies $academy
 * @property CommunicationLogs[] $communicationLogs
 * @property CommunicationSchedules[] $communicationSchedules
 */
class Communication extends \yii\db\ActiveRecord
{

     // Define constants for communication type
     const COMMUNICATION_TYPE_CANCELLATION = 1;
     const COMMUNICATION_TYPE_INVITATION = 2;
     const COMMUNICATION_TYPE_OTHER = 3;
 
     // Define constants for send method
     const SEND_METHOD_WHATSAPP = 1;
     const SEND_METHOD_EMAIL = 2;
     const SEND_METHOD_SMS = 3;
     const SEND_METHOD_NOTIFICATION = 4;
 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'communication';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['communication_type', 'send_method', 'message_text'], 'required'],
            [['communication_type', 'send_method', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['message_text'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'communication_type' => Yii::t('common', 'Communication Type'),
            'message_text' => Yii::t('common', 'Message Text'),
            'send_method' => Yii::t('common', 'Send Method'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
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
     * Gets query for [[CommunicationLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunicationLogs()
    {
        return $this->hasMany(CommunicationLogs::class, ['communication_id' => 'id']);
    }

    /**
     * Gets query for [[CommunicationSchedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunicationSchedules()
    {
        return $this->hasMany(CommunicationSchedules::class, ['communication_id' => 'id']);
    }

     /**
     * Returns communication type options.
     *
     * @return array
     */
    public static function getCommunicationTypeOptions()
    {
        return [
            self::COMMUNICATION_TYPE_CANCELLATION => Yii::t('common', 'Training Cancellation'),
            self::COMMUNICATION_TYPE_INVITATION => Yii::t('common', 'General Invitation'),
            self::COMMUNICATION_TYPE_OTHER => Yii::t('common', 'Other'),
        ];
    }

    /**
     * Returns send method options.
     *
     * @return array
     */
    public static function getSendMethodOptions()
    {
        return [
            self::SEND_METHOD_WHATSAPP => Yii::t('common', 'WhatsApp'),
            self::SEND_METHOD_EMAIL => Yii::t('common', 'Email'),
            self::SEND_METHOD_SMS => Yii::t('common', 'SMS'),
            self::SEND_METHOD_NOTIFICATION => Yii::t('common', 'Notifications'),
        ];
    }

     /**
     * Loads the model with given data.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model and related models if any.
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
}
