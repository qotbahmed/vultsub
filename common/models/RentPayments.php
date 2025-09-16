<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rent_payments".
 *
 * @property int $id
 * @property float|null $amount
 * @property int|null $payment_method
 * @property int|null $rent_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $receipt_number

 *
 * @property Rent $rent
 */
class RentPayments extends \yii\db\ActiveRecord
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
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->payment_method = 1;
        }
    }

    // Dropdown values for payment methods
    public static function getPaymentMethodOptions()
    {
        return self::PAYMENT_METHODS;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rent_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'receipt_number'], 'required'],
            [['amount'], 'number'],
            [
                ['amount'],
                'compare',
                'compareValue' => 0,
                'operator' => '>',
                'message' => Yii::t('common', 'Amount must be greater than zero.')
            ],
            [['payment_method', 'rent_id', 'created_by', 'updated_by', 'receipt_number'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['rent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rent::class, 'targetAttribute' => ['rent_id' => 'id']],
            ['receipt_number', 'validateUniqueReceiptNumberInAcademy'],
            [
                'receipt_number',
                'integer',
                'min' => 1,
                'max' => 2147483647,
                'message' => Yii::t('common', 'Receipt number must be between 1 and 2147483647.')
            ],
        ];
    }



    public function validateUniqueReceiptNumberInAcademy($attribute, $params)
    {
        if (empty($this->receipt_number)) {
            return;
        }

        $academyId = $this->rent->academy_id ?? null;
        if (!$academyId) {
            return;
        }

        $query = self::find()
            ->alias('rp')
            ->innerJoinWith('rent r')
            ->where([
                'r.academy_id' => $academyId,
                'rp.receipt_number' => $this->receipt_number,
            ]);

        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'rp.id', $this->id]);
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
            'rent_id' => Yii::t('common', 'Rent ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'receipt_number' => Yii::t('common', 'Receipt Number'),
        ];
    }

    /**
     * Gets query for [[Rent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRent()
    {
        return $this->hasOne(Rent::class, ['id' => 'rent_id']);
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
        return self::PAYMENT_METHODS[$this->payment_method] ?? 'unknown';
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->recordTransaction();
        }
    }

    public function recordTransaction()
    {
        $academyId = $this->academy->id ?? null;

        if (!$academyId) {
            Yii::error("Academy ID not found for RentPayments ID: {$this->id}");
            return;
        }

        $financialBalance = FinancialBalance::findOrCreate(['academy_id' => $academyId]);

        $typeOfSafe = $this->detectSafeType();
        $financialBalance->updateBalance($this->amount, $typeOfSafe, 'rent');

        $this->createTransaction($financialBalance, $typeOfSafe);
    }

    protected function createTransaction($financialBalance, $typeOfSafe)
    {
        $prefixes = Transactions::getReceiptPrefixes();
        $prefix = isset($prefixes[Transactions::SOURCE_RENT]) ? $prefixes[Transactions::SOURCE_RENT] : '';
        $formattedReceipt = $prefix . $this->receipt_number;
        $transaction = new Transactions([
            'academy_id' => $financialBalance->academy_id,
            'type' => Transactions::TYPE_INCOMING,
            'source' => Transactions::SOURCE_RENT,
            'source_id' => $this->id,
            'amount' => abs($this->amount),
            'payment_method' => $this->payment_method,
            'receipt_number' => $formattedReceipt,
            'financial_balance_id' => $financialBalance->id,
            'type_of_safe' => $typeOfSafe,
            'note' => $this->description ?? '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
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
        return $this->hasOne(Academies::class, ['id' => 'academy_id'])->via('rent');
    }


    public static function getPaymentsMethodOptions()
    {
        return [
            self::METHOD_NETWORK  => Yii::t('common', 'Network'),
            self::METHOD_CASH     => Yii::t('common', 'Cash'),
            self::METHOD_BANK_TRANSFER => Yii::t('common', 'Bank Transfer'),
            self::METHOD_STC_PAY  => Yii::t('common', 'STC Pay'),
            self::METHOD_OTHER => Yii::t('common', 'Other'),

        ];
    }
}
