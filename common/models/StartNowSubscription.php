<?php

namespace common\models;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;

use Yii;

/**
 * This is the model class for table "start_now_subscriptions".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int $status
 * @property string|null $name
 * @property string $phone
 * @property string $academy_name
 * @property int $branches_count
 * @property string $preferred_time
 */
class StartNowSubscription extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const TIME_MORNING = 1;
    const TIME_AFTERNOON = 2;
    const TIME_EVENING = 3;
    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'start_now_subscriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'branches_count'], 'integer'],
            [['phone', 'academy_name', 'branches_count', 'preferred_time'], 'required'],
            [['created_at', 'name', 'academy_name', 'preferred_time'], 'string', 'max' => 255],
            ['phone', 'string', 'length' => 10],
            ['phone', 'match', 'pattern' => '/^05\d{8}$/'],
            ['branches_count', 'integer', 'min' => 1],
            ['preferred_time', 'in', 'range' => array_keys(self::preferredTimeOptions())],
            ['reCaptcha', ReCaptchaValidator2::class,
            'secret'          => Yii::$app->params['reCaptcha.secretKey'],
            'uncheckedMessage'=> 'يرجى التحقق أنك لست روبوتاً.',
        ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'status' => Yii::t('common', 'Status'),
            'name' => Yii::t('common', 'Name'),
            'phone' => Yii::t('common', 'Phone'),
            'academy_name' => Yii::t('common', 'Academy Name'),
            'branches_count' => Yii::t('common', 'Branches Count'),
            'preferred_time' => Yii::t('common', 'Preferred Time'),
            

        ];
    }

    public function loadAll($data)
    {
        return $this->load($data);
    }

    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
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
    public static function preferredTimeOptions()
    {
        return [
            self::TIME_MORNING   => Yii::t('common', 'صباحا'),
            self::TIME_AFTERNOON => Yii::t('common', 'ظهرا'),
            self::TIME_EVENING   => Yii::t('common', 'مساءا'),
        ];
    }

    public function getPreferredTimeText()
    {
        $list = self::preferredTimeOptions();
        return $list[$this->preferred_time] ?? Yii::t('common', 'Not specified');
    }
    public function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }

    public function contactStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">' . Yii::t('backend', 'Not Active') . '</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">' . Yii::t('backend', 'Active') . '</span>'
        ];
    }

    public static function getStatuses($controllerType)
    {
        $statuses = [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];

        return $statuses;
    }
}
