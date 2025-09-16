<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "financial_balance".
 *
 * @property int $id
 * @property int $academy_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property float|null $expenses_total
 * @property float|null $cash
 * @property float|null $bank
 * @property float|null $total_subscription
 * @property float|null $total_rent
 * @property float|null $total_order
 * @property Academies $academy
 */
class FinancialBalance extends \yii\db\ActiveRecord
{
    const TYPE_CASH = 1;
    const TYPE_BANK = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'financial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['cash', 'bank', 'expenses_total', 'total_subscription', 'total_rent', 'total_order'], 'number'],
            [['cash', 'bank', 'expenses_total', 'total_subscription', 'total_rent', 'total_order'], 'default', 'value' => 0.00],
            [['academy_id'], 'required'],
            [['academy_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['expenses_total'], 'number'],
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
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'expenses_total' => Yii::t('common', 'Expenses Total'),
            'cash' => Yii::t('common', 'Cash Balance'),
            'bank' => Yii::t('common', 'Bank Balance'),
            'expenses_total' => Yii::t('common', 'Total Expenses'),
            'total_subscription' => Yii::t('common', 'Total Subscriptions'),
            'total_rent' => Yii::t('common', 'Total Rent'),
            'total_order' => Yii::t('common', 'Total Orders'),
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

    public static function findOrCreate($condition, $defaultAttributes = [])
    {
        $model = self::find()->where($condition)->one();

        if (!$model) {
            $attributes = array_merge($condition, $defaultAttributes);
            $model = new self($attributes);
            $model->save(false);
        }

        return $model;
    }

    public function updateBalance($amount, $typeOfSafe, $category)
    {
        if ($typeOfSafe === Transactions::SAFE_TYPE_CASH) {
            $this->cash += $amount;
        } else {
            $this->bank += $amount;
        }

        if ($category === 'expenses') {
            $this->expenses_total += $amount;
        } elseif (in_array($category, ['subscription', 'rent', 'order'])) {
            $field = "total_{$category}";
            $this->$field += $amount;
        }

        $this->save(false);
    }


    public function transferToCash($amount, $notes = null)
    {
        if ($amount <= 0) {
            return ['success' => false, 'message' => 'المبلغ يجب أن يكون أكبر من الصفر'];
        }

        if ($this->bank < $amount) {
            return ['success' => false, 'message' => 'الرصيد البنكي غير كافٍ للتحويل'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->bank -= $amount;
            $this->cash += $amount;

            if (!$this->save()) {
                throw new \Exception('فشل في تحديث الرصيد');
            }

            $bankTransaction = new Transactions([
                'academy_id'            => $this->academy_id,
                'financial_balance_id'  => $this->id,
                'type'                  => Transactions::TYPE_TRANSFER,
                'source'                => Transactions::SOURCE_TRANSFER,
                'source_id'             => $this->id,
                'amount'                => -abs($amount),
                'payment_method'        => Transactions::PAYMENT_METHOD_BANK_TRANSFER,
                'type_of_safe'          => Transactions::SAFE_TYPE_BANK,
                'note'                  => $notes,
                'created_by'            => Yii::$app->user->id,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
                'updated_by'            => Yii::$app->user->id,
            ]);
            if (!$bankTransaction->save()) {
                throw new \Exception('فشل في تسجيل معاملة خصم البنك');
            }

            $cashTransaction = new Transactions([
                'academy_id'            => $this->academy_id,
                'financial_balance_id'  => $this->id,
                'type'                  => Transactions::TYPE_TRANSFER,
                'source'                => Transactions::SOURCE_TRANSFER,
                'source_id'             => $this->id,
                'amount'                => $amount,
                'payment_method'        => Transactions::PAYMENT_METHOD_BANK_TRANSFER,
                'type_of_safe'          => Transactions::SAFE_TYPE_CASH,
                'note'                  => $notes,
                'created_by'            => Yii::$app->user->id,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
                'updated_by'            => Yii::$app->user->id,
            ]);
            if (!$cashTransaction->save()) {
                throw new \Exception('فشل في تسجيل معاملة إضافة الكاش');
            }

            $transaction->commit();
            return ['success' => true, 'message' => 'تم التحويل بنجاح'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function transferBetweenSafes($amount, $fromSafe, $toSafe, $notes = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($fromSafe === Transactions::SAFE_TYPE_CASH) {
                $this->cash -= $amount;
            } else {
                $this->bank -= $amount;
            }

            if ($toSafe === Transactions::SAFE_TYPE_CASH) {
                $this->cash += $amount;
            } else {
                $this->bank += $amount;
            }

            if (!$this->save()) {
                throw new \Exception('فشل في تحديث الرصيد');
            }

            $paymentMethod = ($toSafe === Transactions::SAFE_TYPE_BANK)
                ? Transactions::PAYMENT_METHOD_CASH
                : Transactions::PAYMENT_METHOD_BANK_TRANSFER;

            $fromTransaction = new Transactions([
                'academy_id'           => $this->academy_id,
                'financial_balance_id' => $this->id,
                'type'                 => Transactions::TYPE_TRANSFER,
                'source'               => Transactions::SOURCE_TRANSFER,
                'source_id'            => $this->id,
                'amount'               => -abs($amount),
                'payment_method'       => $paymentMethod,
                'type_of_safe'         => $fromSafe,
                'note'                 => $notes,
                'created_by'           => Yii::$app->user->id,
                'updated_by'           => Yii::$app->user->id,
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);

            if (!$fromTransaction->save()) {
                throw new \Exception('فشل في تسجيل المعاملة للخزنة المرسلة');
            }

            $toTransaction = new Transactions([
                'academy_id'           => $this->academy_id,
                'financial_balance_id' => $this->id,
                'type'                 => Transactions::TYPE_TRANSFER,
                'source'               => Transactions::SOURCE_TRANSFER,
                'source_id'            => $this->id,
                'amount'               => abs($amount),
                'payment_method'       => $paymentMethod,
                'type_of_safe'         => $toSafe,
                'note'                 => $notes,
                'created_by'           => Yii::$app->user->id,
                'updated_by'           => Yii::$app->user->id,
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);

            if (!$toTransaction->save()) {
                throw new \Exception('فشل في تسجيل المعاملة للخزنة المستقبلة');
            }

            $transaction->commit();
            return ['success' => true];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
