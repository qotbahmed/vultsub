<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_rate".
 *
 * @property int $id
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property int|null $created_by
 * @property int $user_id
 * @property int $academy_id
 * @property float|null $total_rate
 * @property string|null $comment
 *
 * @property Academies $academy
 * @property User $user
 */
class UserRate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_by', 'created_by', 'user_id', 'academy_id'], 'integer'],
            [['user_id', 'academy_id'], 'required'],
            [['total_rate'], 'number'],
            [['updated_at', 'created_at'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_by' => Yii::t('backend', 'Created By'),
            'user_id' => Yii::t('backend', 'User ID'),
            'academy_id' => Yii::t('backend', 'Academy ID'),
            'total_rate' => Yii::t('backend', 'Total Rate'),
            'comment' => Yii::t('backend', 'Comment'),
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}