<?php

namespace common\models;
use common\models\Orders;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $serial_number
 * @property int $type
 * @property int $related_id
 * @property float $amount
 * @property float $tax
 * @property int|null $academy_id
 * @property int $qr_path
 * @property int $qr_base_url
 * @property string|null $created_at
 *
 * @property Academies $academy
 * @property Subscription|null $subscription
 * @property Rent|null $rent
 * @property Order|null $order
 */
class Invoices extends \yii\db\ActiveRecord
{
    const BILL_TYPE_SUBSCRIPTION = 1;
    const BILL_TYPE_RENT = 2;
    const BILL_TYPE_ORDER = 3;
    public $academyId;

    public static function getBillTypes()
    {
        return [
            self::BILL_TYPE_SUBSCRIPTION => Yii::t('common', 'Subscription'),
            self::BILL_TYPE_RENT => Yii::t('common', 'Rent'),
            self::BILL_TYPE_ORDER => Yii::t('common', 'Order'),
        ];
    }

    public function init()
    {
        parent::init();
        // Set the table name based on the academy id during initialization
        $this->setTableName($this->academyId);
    }

    public function setTableName($academyId)
    {
        if ($academyId) {
            $this->tableName = 'invoices_' . $academyId;
        } else {
            $this->tableName = '{{%invoices}}'; // Default table if academyId is not set
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoices}}'; // Return default table name if needed
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_number', 'type', 'related_id', 'amount', 'tax'], 'required'],
            [['serial_number', 'type', 'related_id', 'academy_id'], 'integer'],
            [['amount', 'tax'], 'number'],
            [['created_at'], 'safe'],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'serial_number' => Yii::t('common', 'Serial Number'),
            'type' => Yii::t('common', 'Invoice Type'),
            'related_id' => Yii::t('common', 'Related ID'),
            'amount' => Yii::t('common', 'Amount'),
            'tax' => Yii::t('common', 'Tax'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    /**
     * Gets query for related Subscription if the invoice type is 1.
     *
     * @return \yii\db\ActiveQuery|null
     */
    public function getSubscription()
    {
        if ($this->type == 1) {
            return $this->hasOne(Subscription::class, ['id' => 'related_id']);
        }
        return null;
    }

    /**
     * Gets query for related Rent if the invoice type is 2.
     *
     * @return \yii\db\ActiveQuery|null
     */
    public function getRent()
    {
        if ($this->type == 2) {
            return $this->hasOne(Rent::class, ['id' => 'related_id']);
        }
        return null;
    }

    /**
     * Gets query for related Order if the invoice type is 3.
     *
     * @return \yii\db\ActiveQuery|null
     */
    public function getOrder()
    {
        if ($this->type == 3) {
            return $this->hasOne(Orders::class, ['id' => 'related_id']);
        }
        return null;
    }
}
