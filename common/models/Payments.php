<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property float|null $amount
 * @property int|null $payment_method
 * @property int $subscription_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $note
 * @property int|null $receipt_number
 *
 * @property Subscription $subscription
 */
class Payments extends \yii\db\ActiveRecord
{
    const METHOD_NETWORK = 1;
    const METHOD_CASH = 2;
    const METHOD_BANK_TRANSFER = 3;
    const METHOD_STC_PAY = 4;
    const METHOD_OTHER = 5;


    const PAYMENT_METHODS = [
        self::METHOD_NETWORK => 'شبكة',
        self::METHOD_CASH => 'نقدي',
        self::METHOD_BANK_TRANSFER => 'تحويل بنكي',
        self::METHOD_STC_PAY => 'STC Pay',
        self::METHOD_OTHER => 'أخرى',

    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'receipt_number'], 'required'],
            [['amount', 'receipt_number'], 'number'],
            [
                ['amount'],
                'compare',
                'compareValue' => 0,
                'operator' => '>=',
                'message' => Yii::t('common', 'Amount must be greater than or equal to zero.')
            ],

            [['payment_method', 'subscription_id', 'created_by', 'updated_by'], 'integer'],
            [['subscription_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['subscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::class, 'targetAttribute' => ['subscription_id' => 'id']],
            [['payment_method'], 'in', 'range' => array_keys(self::PAYMENT_METHODS)],
            [['note'], 'string',],
            ['receipt_number', 'validateUniqueReceiptInAcademy'],
            [
                'receipt_number',
                'integer',
                'min' => 1,
                'max' => 2147483647,
                'message' => Yii::t('common', 'Receipt number must be between 1 and 2147483647.')
            ],

        ];
    }
    public function validateUniqueReceiptInAcademy($attribute, $params)
    {
        if (empty($this->receipt_number)) {
            return;
        }

        $academyId = $this->academy->id ?? null;
        if (!$academyId) {
            return;
        }

        $query = self::find()
            ->alias('p')
            ->innerJoinWith('subscription s')
            ->where([
                's.academy_id' => $academyId,
                'p.receipt_number' => $this->receipt_number,
            ]);

        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'p.id', $this->id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, Yii::t('common', 'This receipt number is already used in the current academy.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'amount' => Yii::t('common', 'Amount'),
            'payment_method' => Yii::t('common', 'Payment Method'),
            'subscription_id' => Yii::t('common', 'Subscription ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'note' => Yii::t('common', 'Note'),
            'receipt_number' => Yii::t('common', 'Receipt Number'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->payment_method = 1;
        }
    }

    /**
     * Gets query for [[Subscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
    }
    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id'])->via('subscription');
    }

    /**
     * Loads the model with data.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model with optional validation and attribute selection.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     * 
     * @return bool Whether the deletion was successful.
     * @throws \Exception
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add additional deletion logic here, if necessary.

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
    public function getPaymentMethodText()
    {
        $paymentMethods = [
            1 => Yii::t('common', 'Network'),
            2 => Yii::t('common', 'Cash'),
            3 => Yii::t('common', 'Bank Transfer'),
            4 => Yii::t('common', 'STC Pay'),
            5 => Yii::t('common', 'Other'),
        ];
        return $paymentMethods[$this->payment_method] ?? Yii::t('common', 'Undefined');
    }




    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->recordTransaction();
        }
    }


    protected function recordTransaction()
    {
        $academyId = $this->academy->id ?? null;

        if (!$academyId) {
            Yii::error("Academy ID not found for payment #{$this->id}");
            return;
        }

        $financialBalance = FinancialBalance::findOrCreate(['academy_id' => $academyId]);

        $typeOfSafe = $this->detectSafeType();
        $financialBalance->updateBalance($this->amount, $typeOfSafe, 'subscription');

        $this->createTransaction($financialBalance, $typeOfSafe);
    }


    private function detectSafeType()
    {
        return in_array($this->payment_method, [
            self::METHOD_BANK_TRANSFER,
            self::METHOD_NETWORK,
            self::METHOD_STC_PAY
        ]) ? Transactions::SAFE_TYPE_BANK : Transactions::SAFE_TYPE_CASH;
    }

    private function createTransaction($financialBalance, $typeOfSafe)
    {
        $prefixes = Transactions::getReceiptPrefixes();
        $prefix = isset($prefixes[Transactions::SOURCE_SUBSCRIPTION]) ? $prefixes[Transactions::SOURCE_SUBSCRIPTION] : '';
        $formattedReceipt = $prefix . $this->receipt_number;
        $transaction = new Transactions([
            'academy_id' => $financialBalance->academy_id,
            'financial_balance_id' => $financialBalance->id,
            'type' => Transactions::TYPE_INCOMING,
            'source' => Transactions::SOURCE_SUBSCRIPTION,
            'source_id' => $this->id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'type_of_safe' => $typeOfSafe,
            'receipt_number' => $formattedReceipt,
            'note' => 'دفع اشتراك - إيصال رقم: ' . $this->receipt_number,
            'created_by' => Yii::$app->user->id,
            'updated_by' => Yii::$app->user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);

        $transaction->save();
    }

    public static function getAllPaymentMethods()
    {
        return [
            self::METHOD_NETWORK => Yii::t('common', 'Network'),
            self::METHOD_CASH => Yii::t('common', 'Cash'),
            self::METHOD_BANK_TRANSFER => Yii::t('common', 'Bank Transfer'),
            self::METHOD_STC_PAY => Yii::t('common', 'STC Pay'),
            self::METHOD_OTHER => Yii::t('common', 'Other'),

        ];
    }
}
