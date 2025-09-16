<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;


/**
 * This is the model class for table "join_as_partner".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 * @property string|null $name
 * @property string|null $contact_email
 * @property string $contact_phone
 * @property int|null $explore
 * @property string $reCaptcha  

 */
class JoinAsPartner extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    
    const EXPLORE_PARTNERSHIP = 1;
    const EXPLORE_AMBASSADOR = 2;
    const EXPLORE_OTHER = 3;
    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'join_as_partner';
    }

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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'contact_email', 'contact_phone', 'explore'], 'required'], 
            [['status', 'explore'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'contact_email'], 'string', 'max' => 255],
            [['contact_phone'], 'string', 'max' => 25],
            ['contact_email', 'email'], 
            ['explore', 'in', 'range' => array_keys(self::getExploreOptions())],
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
            'id' => Yii::t('backend', 'ID'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'status' => Yii::t('backend', 'Status'),
            'name' => Yii::t('backend', 'Name'),
            'contact_email' => Yii::t('backend', 'Contact Email'),
            'contact_phone' => Yii::t('backend', 'Contact Phone'),
            'explore' => Yii::t('backend', 'Explores'),
            'reCaptcha'     => 'التحقق الأمنية',

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

    public function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];
    }

    public static function getExploreOptions()
    {
        return [
            self::EXPLORE_PARTNERSHIP => Yii::t('backend', 'شراكة او استثمار'),
            self::EXPLORE_AMBASSADOR => Yii::t('backend', 'سفير او معاون'),
            self::EXPLORE_OTHER => Yii::t('backend', 'أمر آخر'),
        ];
    }

    public function getExploreLabel()
    {
        $options = self::getExploreOptions();
        return $options[$this->explore] ?? Yii::t('backend', 'غير محدد');
    }
}