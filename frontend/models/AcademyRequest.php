<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "academy_requests".
 *
 * @property int $id
 * @property string $academy_name
 * @property string $manager_name
 * @property string $email
 * @property string $phone
 * @property string $city
 * @property int $branches_count
 * @property string $sports
 * @property string $description
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class AcademyRequest extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academy_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_name', 'manager_name', 'email', 'phone', 'city'], 'required'],
            [['branches_count'], 'integer', 'min' => 1],
            [['description', 'sports'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['academy_name', 'manager_name', 'city'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 50],
            [['status'], 'default', 'value' => 'pending'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'academy_name' => 'Academy Name',
            'manager_name' => 'Manager Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'city' => 'City',
            'branches_count' => 'Branches Count',
            'sports' => 'Sports',
            'description' => 'Description',
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
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    /**
     * Get sports as array
     */
    public function getSportsArray()
    {
        return $this->sports ? explode(',', $this->sports) : [];
    }

    /**
     * Set sports from array
     */
    public function setSportsArray($sports)
    {
        $this->sports = is_array($sports) ? implode(',', $sports) : $sports;
    }
}
