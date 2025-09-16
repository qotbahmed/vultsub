<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $academy_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $type
 * @property int|null $source
 * @property int $source_id
 * @property float|null $amount
 * @property int|null $payment_method
 * @property int $financial_balance_id
 * @property int|null $type_of_safe
 * @property string|null $note
 * @property string|null $receipt_number
 *
 * @property Academies $academy
 */
class Transactions extends \yii\db\ActiveRecord
{
    const TYPE_INCOMING = 1;
    const TYPE_OUTGOING = 2;
    const TYPE_TRANSFER = 3;
    const TYPE_FINANCIAL = 4;

    const SOURCE_SUBSCRIPTION = 1;
    const SOURCE_RENT = 2;
    const SOURCE_PRODUCT = 3;
    const SOURCE_EXPENSE = 4;
    const SOURCE_TRANSFER = 5;
    const SOURCE_PARTNER = 6;

    const PAYMENT_METHOD_CASH = 2;
    const PAYMENT_METHOD_BANK_TRANSFER = 3;
    const PAYMENT_METHOD_NETWORK = 1;
    const PAYMENT_METHOD_STC_PAY = 4;
    const METHOD_OTHER = 5;


    const SAFE_TYPE_CASH = 1;
    const SAFE_TYPE_BANK = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'amount'], 'default', 'value' => null],
            [['payment_method'], 'default', 'value' => 3],
            [['academy_id', 'source_id'], 'required'],
            [['academy_id', 'created_by', 'updated_by', 'type', 'source', 'source_id', 'payment_method'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
            [['receipt_number'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['financial_balance_id'], 'required'],
            [['financial_balance_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinancialBalance::class, 'targetAttribute' => ['financial_balance_id' => 'id']],
            [['type_of_safe'], 'integer'],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'type' => Yii::t('common', 'Type of Transaction'),
            'source' => Yii::t('common', 'Source'),
            'source_id' => Yii::t('common', 'Source ID'),
            'amount' => Yii::t('common', 'Amount'),
            'payment_method' => Yii::t('common', 'Payment Method'),
            'financial_balance_id' => Yii::t('common', 'Balance ID'),
            'type_of_safe' => Yii::t('common', 'Safe Type'),
            'note' => Yii::t('common', 'Notes'),
            'receipt_number' => Yii::t('common', 'رقم الإيصال'),
            'order_receipt' => Yii::t('common', 'Order Receipt'),
            'type_of_safe' => Yii::t('common', 'Type of Safe'),
            'receipt_number' => Yii::t('common', 'Receipt Number'),

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

    public static function getTypeLabels()
    {
        return [
            self::TYPE_INCOMING => Yii::t('common', 'Income'),
            self::TYPE_OUTGOING => Yii::t('common', 'Expense'),
            self::TYPE_TRANSFER => Yii::t('common', 'Transfer Between Safes'),
            self::TYPE_FINANCIAL => Yii::t('common', 'Partner Funding'),
        ];
    }

    public function getTypeText()
    {
        return self::getTypeLabels()[$this->type] ?? 'غير معروف';
    }

    public static function getSourceLabels()
    {
        return [
            self::SOURCE_SUBSCRIPTION => Yii::t('common', 'Subscription'),
            self::SOURCE_RENT => Yii::t('common', 'Rent'),
            self::SOURCE_PRODUCT => Yii::t('common', 'Product'),
            self::SOURCE_EXPENSE => Yii::t('common', 'Expense'),
            self::SOURCE_TRANSFER => Yii::t('common', 'Transfer'),
            self::SOURCE_PARTNER => Yii::t('common', 'Partner'),
        ];
    }

    public function getSourceText()
    {
        return self::getSourceLabels()[$this->source] ?? 'غير معروف';
    }
    public static function getSafeTypeLabels()
    {
        return [
            self::SAFE_TYPE_CASH => Yii::t('common', 'Cash Safe'),
            self::SAFE_TYPE_BANK => Yii::t('common', 'Bank Safe'),
        ];
    }


    public function getSafeTypeText()
    {
        return self::getSafeTypeLabels()[$this->type_of_safe] ?? 'غير معروف';
    }

    public function getBalance()
    {
        return $this->hasOne(FinancialBalance::class, ['id' => 'financial_balance_id']);
    }
    public function getPaymentMethodText()
    {
        return self::getPaymentMethodLabels()[$this->payment_method] ?? ' _';
    }

    public static function getPaymentMethodLabels()
    {
        return [
            self::PAYMENT_METHOD_CASH => Yii::t('common', 'Cash'),
            self::PAYMENT_METHOD_BANK_TRANSFER => Yii::t('common', 'Bank Transfer'),
            self::PAYMENT_METHOD_NETWORK => Yii::t('common', 'Payment Network'),
            self::PAYMENT_METHOD_STC_PAY => Yii::t('common', 'STC Pay'),
            self::METHOD_OTHER => Yii::t('common', 'Other'),

        ];
    }
    public static function getReceiptPrefixes()
    {
        return [
            self::SOURCE_SUBSCRIPTION => 'SUB',
            self::SOURCE_PRODUCT    => 'PRO',
            self::SOURCE_EXPENSE    => 'EXP',
            self::SOURCE_RENT       => 'REN',
            self::SOURCE_TRANSFER   => 'TRF',
            self::SOURCE_PARTNER    => 'PAR',
        ];
    }

    public function getRelatedSubscription()
    {
        if ($this->source == self::SOURCE_SUBSCRIPTION) {
            $payment = \common\models\Payments::findOne($this->source_id);
            if ($payment !== null) {
                return $payment->subscription;
            }
        }
        return null;
    }
    public function getRelatedExpense()
    {
        if ($this->source == self::SOURCE_EXPENSE) {
            return \common\models\Expenses::findOne($this->source_id);
        }
        return null;
    }


    public function getRelatedPaymentStore()
    {
        if ($this->source == self::SOURCE_PRODUCT) {
            return \common\models\PaymentsStore::findOne($this->source_id);
        }
        return null;
    }


    public function getRelatedRentPayment()
    {
        if ($this->source == self::SOURCE_RENT) {
            return \common\models\RentPayments::findOne($this->source_id);
        }
        return null;
    }
}
