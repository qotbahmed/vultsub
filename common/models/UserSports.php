<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_sports".
 *
 * @property int $id
 * @property int $user_id
 * @property int $sport_id
 * @property string|null $updated_at
 * @property string|null $created_at
 
 *
 * @property UserProfile $user
 */
class UserSports extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_sports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'sport_id'], 'required'],
            [['user_id', 'sport_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProfile::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'sport_id' => Yii::t('common', 'Sport ID'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }
}
