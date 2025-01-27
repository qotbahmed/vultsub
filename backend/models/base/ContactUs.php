<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;
use backend\models\query\ContactUsQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the base model class for table "contact_us".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $title
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 */
class ContactUs extends ActiveRecord
{

    use RelationTrait;


    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact_us';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name'),
            'phone' => Yii::t('backend', 'Phone'),
            'email' => Yii::t('backend', 'Email'),
            'title' => Yii::t('backend', 'Company'),
            'message' => Yii::t('backend', 'Message'),
            'preferred_contact_option' => Yii::t('backend', 'Preferred Contact Option'),
        ];
    }

/**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return ContactUsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactUsQuery(get_called_class());
    }
}
