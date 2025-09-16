<?php

namespace common\models;

use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;

/**
 * This is the model class for table "contact_works".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int $status
 * @property string|null $name
 * @property string|null $company_name
 * @property string|null $contact_email
 * @property string $contact_phone
 * @property string|null $description
 */
class ContactWorksVult extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact_works_vult';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['contact_phone'], 'required'],
            [['description'], 'string'],
            [['created_at', 'name', 'company_name', 'contact_email'], 'string', 'max' => 255],
            [['contact_phone'], 'string', 'max' => 25],
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
            'company_name' => Yii::t('common', 'Company Name'),
            'contact_email' => Yii::t('common', 'Contact Email'),
            'contact_phone' => Yii::t('common', 'Contact Phone'),
            'description' => Yii::t('common', 'Description'),
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
