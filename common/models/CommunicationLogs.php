<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "communication_logs".
 *
 * @property int $id
 * @property int|null $communication_id
 * @property int|null $parent_id
 * @property string|null $mobile
 * @property string|null $email
 * @property string|null $sent_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $academy_id
 *
 * @property Academies $academy
 * @property Communication $communication
 * @property User $parent
 */
class CommunicationLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'communication_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['communication_id', 'parent_id', 'created_by', 'updated_by', 'academy_id'], 'integer'],
            [['sent_at', 'created_at', 'updated_at'], 'safe'],
            [['mobile', 'email'], 'string', 'max' => 255],
            [['communication_id'], 'exist', 'skipOnError' => true, 'targetClass' => Communication::class, 'targetAttribute' => ['communication_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['parent_id' => 'id']],
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
            'communication_id' => Yii::t('common', 'Communication ID'),
            'parent_id' => Yii::t('common', 'Parent ID'),
            'mobile' => Yii::t('common', 'Mobile'),
            'email' => Yii::t('common', 'Email'),
            'sent_at' => Yii::t('common', 'Sent At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
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
     * Gets query for [[Communication]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommunication()
    {
        return $this->hasOne(Communication::class, ['id' => 'communication_id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(User::class, ['id' => 'parent_id']);
    }
}
