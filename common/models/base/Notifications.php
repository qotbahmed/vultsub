<?php

namespace common\models\base;

use common\models\Subscription;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\query\NotificationsQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\models\User;

/**
 * This is the base model class for table "notifications".
 *
 * @property integer $id
 * @property string $topic
 * @property integer $key_id
 * @property integer $from_id
 * @property integer $to_id
 * @property integer $request_id
 * @property string $module
 * @property integer $module_id
 * @property string $title_ar
 * @property string $title_en
 * @property string $route
 * @property string $message_ar
 * @property string $message_en
 * @property string $action
 * @property integer $seen
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Notifications extends ActiveRecord
{

    use RelationTrait;

    const TYPE_NEW_SUBSCRIPTION = 1;
    const TYPE_NEW_PRIVATE_REQUEST = 2;
    const TYPE_ACCEPT_NANNY_OFFER = 3;
    const TYPE_UPDATE_REQUEST_STATUS = 4;
    const TYPE_NANNY_APPROVAL = 5;
    const TYPE_NEW_REQUEST_OFFER = 6;
    const TYPE_UPDATE_REQUEST_TRACKING_STATUS = 7;
    const TYPE_RATE = 8;
    const TYPE_CHAT = 9;
    const TYPE_EXTEND_SESSION = 10;
    const TYPE_UPDATE_EXTEND_SESSSION_STATUS = 11;
    const TYPE_NANNY_NOT_APPROVED = 12;
    const TYPE_SESSION_FULL_REFUND = 13;
    const TYPE_SESSION_HALF_REFUND = 14;
    const TYPE_SESSION_PAID = 15;
    const TYPE_CALL = 16;
    const  TYPE_PAYMENT_PROCESSED = 17;
    const TYPE_REFUND_PROCESSED = 18;
    const TYPE_SUBSCRIPTION_RENEWED = 19;
    const TYPE_SUBSCRIPTION_DEACTIVATED = 20;
    const TYPE_SUBSCRIPTION_ACTIVATED = 21;
    const TYPE_APPOINTMENT_BOOKED = 22;
    const TYPE_ATTENDANCE_RECORDED = 23;
    const TYPE_ABSENCE_RECORDED = 24;
    const TYPE_EXTERNAL_PLAYGROUND = 25;
    const NOTIFICATION_NOT_SEEN = 0;
    const NOTIFICATION_SEEN = 1;

    const REQUEST_WILL_END = 2;
    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            ''
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_id', 'to_id', 'module_id', 'created_by', 'updated_by', 'key_id', 'request_id'], 'integer'],
            [['payload'], 'safe'],
            [['topic', 'module', 'title_ar', 'title_en', 'message_ar', 'route', 'message_en', 'action', 'created_at', 'updated_at'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'key_id' => Yii::t('backend', 'Key'),
            'topic' => Yii::t('backend', 'Topic'),
            'from_id' => Yii::t('backend', 'From ID'),
            'to_id' => Yii::t('backend', 'To ID'),
            'module' => Yii::t('backend', 'Module'),
            'module_id' => Yii::t('backend', 'Module ID'),
            'title_ar' => Yii::t('backend', 'Title Ar'),
            'title_en' => Yii::t('backend', 'Title En'),
            'message_ar' => Yii::t('backend', 'Message Ar'),
            'message_en' => Yii::t('backend', 'Message En'),
            'action' => Yii::t('backend', 'Action'),
            'seen' => Yii::t('backend', 'Seen'),
            'route' => Yii::t('backend', 'Route'),
            'request_id' => Yii::t('backend', 'Request ID'),
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
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getFromUser()
    {
        return $this->hasOne(User::class, ['id' => 'from_id']);
    }

    public function getToUser()
    {
        return $this->hasOne(User::class, ['id' => 'to_id']);
    }

    /**
     * @inheritdoc
     * @return NotificationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationsQuery(get_called_class());
    }
}
