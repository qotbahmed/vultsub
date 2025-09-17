<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "players".
 *
 * @property int $id
 * @property int $academy_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $date_of_birth
 * @property string $sport
 * @property string $level
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Player extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'players';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'date_of_birth', 'sport'], 'required'],
            [['academy_id'], 'integer'],
            [['date_of_birth', 'created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'phone', 'sport', 'level', 'status'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['status'], 'default', 'value' => 'active'],
            [['level'], 'default', 'value' => 'beginner'],
            [['academy_id'], 'default', 'value' => 1], // Temporary default for testing
        ];
    }
    
    /**
     * Scenarios for different operations
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['name', 'email', 'phone', 'date_of_birth', 'sport', 'level', 'status'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'academy_id' => 'Academy ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'date_of_birth' => 'Date of Birth',
            'sport' => 'Sport',
            'level' => 'Level',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($insert) {
                $this->created_at = $now;
            }
            $this->updated_at = $now;
            return true;
        }
        return false;
    }

    /**
     * Get academy relation
     */
    public function getAcademy()
    {
        return $this->hasOne(\common\models\Academy::class, ['id' => 'academy_id']);
    }
}
