<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payments_store".
 *
 * @property int $id
 * @property float|null $price
 * @property int|null $payment_method
 * @property int $order_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $receipt_number
 *
 * @property Orders $order
 */
class PaymentsStore extends \yii\db\ActiveRecord
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
        return 'payments_store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['payment_method', 'order_id', 'created_by', 'updated_by', 'receipt_number'], 'integer'],
            [['order_id'], 'required'],
            ['receipt_number', 'validateUniqueReceiptNumberInAcademy'],

            [['created_at', 'updated_at'], 'safe'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
            ['payment_method', 'in', 'range' => array_keys(self::PAYMENT_METHODS)],
            ['price', 'number', 'min' => 0, 'message' => Yii::t('common', 'Price must be a positive number.')],


        ];
    }
    public function validateUniqueReceiptNumberInAcademy($attribute, $params)
    {
        if (empty($this->receipt_number)) {
            return;
        }

        $academyId = $this->order->academy_id ?? null;
        if (!$academyId) {
            return;
        }

        $query = self::find()
            ->alias('ps')
            ->innerJoinWith('order o')
            ->where([
                'o.academy_id' => $academyId,
                'ps.receipt_number' => $this->receipt_number,
            ]);

        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'ps.id', $this->id]);
        }

        if ($query->exists()) {
            $this->addError(
                $attribute,
                Yii::t('common', 'This receipt number is already used in the current academy.')
            );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'price' => Yii::t('common', 'Price'),
            'payment_method' => Yii::t('common', 'Payment Method'),
            'order_id' => Yii::t('common', 'Order ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'receipt_number' => Yii::t('common', 'Receipt Number'),
        ];
    }
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->payment_method = 1;
        }
    }
    public static function getPaymentMethods()
    {
        return [
            1 => Yii::t('common', 'Network'),
            2 => Yii::t('common', 'Cash'),
            3 => Yii::t('common', 'Bank transfer'),
            4 => Yii::t('common', 'STC Pay'),
            5 => Yii::t('common', 'Other'),
        ];
    }

    public function getPaymentMethodText()
    {
        $methods = self::getPaymentMethods();
        return isset($methods[$this->payment_method]) ? $methods[$this->payment_method] : Yii::t('common', 'Unknown');
    }
    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::class, ['id' => 'order_id']);
    }

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
            Yii::error("Academy ID not found for PaymentsStore ID: {$this->id}");
            return;
        }

        $financialBalance = FinancialBalance::findOrCreate(['academy_id' => $academyId]);

        $typeOfSafe = $this->detectSafeType();
        $financialBalance->updateBalance($this->price, $typeOfSafe, 'order');

        $this->createTransaction($financialBalance, $typeOfSafe);
    }

    protected function createTransaction($financialBalance, $typeOfSafe)
    {
        $prefixes = Transactions::getReceiptPrefixes();
        $prefix = isset($prefixes[Transactions::SOURCE_PRODUCT]) ? $prefixes[Transactions::SOURCE_PRODUCT] : '';
        $formattedReceipt = $prefix . $this->receipt_number;
        $transaction = new Transactions([
            //'academy_id' => $this->order->academy_id,
            'academy_id' => $financialBalance->academy_id,
            'type' => Transactions::TYPE_INCOMING,
            'source' => Transactions::SOURCE_PRODUCT,
            'source_id' => $this->id,
            'amount' => abs($this->price),
            'receipt_number' => $formattedReceipt,

            'payment_method' => $this->payment_method,
            'financial_balance_id' => $financialBalance->id,
            'type_of_safe' => $typeOfSafe,
            'note' => Yii::t('common', 'دفع للطلب') . ' ' . $this->order_id . ' - ايصال: ' . $this->receipt_number,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_by' => Yii::$app->user->id,
            'updated_by' => Yii::$app->user->id,
        ]);

        $transaction->save(false);
    }

    protected function detectSafeType()
    {
        return ($this->payment_method == self::METHOD_CASH || $this->payment_method == self::METHOD_OTHER)
            ? Transactions::SAFE_TYPE_CASH
            : Transactions::SAFE_TYPE_BANK;
    }

    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id'])->via('order');
    }
}
