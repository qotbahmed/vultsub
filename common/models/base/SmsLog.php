<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\SmsLogQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "sms_log".
 *
 * @property integer $id
 * @property string $mobile
 * @property integer $user_id
 * @property integer $type
 * @property string $expire_at
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $otp
 */
class SmsLog extends ActiveRecord
{

    use RelationTrait;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['user_id', 'created_by', 'updated_by','otp'], 'integer'],
            [['type', 'status'], 'integer'],
            [['mobile', 'expire_at', 'created_at', 'updated_at'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_log';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'mobile' => Yii::t('backend', 'Mobile'),
            'user_id' => Yii::t('backend', 'User ID'),
            'type' => Yii::t('backend', 'Type'),
            'expire_at' => Yii::t('backend', 'Expire At'),
            'status' => Yii::t('backend', 'Status'),
        ];
    }

/**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return SmsLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SmsLogQuery(get_called_class());
    }
}
