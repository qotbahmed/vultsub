<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "districts".
 *
 * @property int $id
 * @property int $city_id
 * @property string $title
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $user_id
 *
 * @property Cities $city
 */
class Districts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'districts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'title'], 'required'],
            [['city_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'city_id' => Yii::t('common', 'City'),
            'title' => Yii::t('common', 'Name'),
            'created_at' => Yii::t('common', 'Created At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'user_id' => Yii::t('common', 'User ID'),
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
{
    return $this->hasOne(Cities::class, ['id' => 'city_id']);
}


    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    public function getUser()
{
    return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
}
}
