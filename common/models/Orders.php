<?php

namespace common\models;
use api\helpers\SubscriptionHelper;
use common\models\UserProfile;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int|null $academy_id
 * @property int $user_id
 * @property float|null $price
 * @property float|null $tax_value
 * @property float|null $total_price
 * @property float|null $amount_paid
 * @property float $remaining_amount
 * @property int|null $payment_status
 * @property int|null $order_status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $notification_preference
 *
 * @property Academies $academy
 * @property OrderDetails[] $orderDetails
 * @property User $user
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_CANCELLED = 3;
    const PARTIAL_PAYMENT= 0;
    const FULL_PAYMENT = 1;
    const NO_PAYMENT = 2;
    const REFUNDED= 3;
    public $payment_method;
  
 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_id', 'user_id', 'payment_status', 'order_status', 'created_by', 'updated_by', 'notification_preference'], 'integer'],
            [['user_id', 'remaining_amount'], 'required'],
            [['price', 'tax_value', 'total_price', 'amount_paid', 'remaining_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['search_type', 'search_value'], 'safe'], 

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
            'user_id' => Yii::t('common', 'User ID'),
            'price' => Yii::t('common', 'Price'),
            'tax_value' => Yii::t('common', 'Tax Value'),
            'total_price' => Yii::t('common', 'Total Price'),
            'amount_paid' => Yii::t('common', 'Amount Paid'),
            'remaining_amount' => Yii::t('common', 'Remaining Amount'),
            'payment_status' => Yii::t('common', 'Payment Status'),
            'order_status' => Yii::t('common', 'Order Status'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'notification_preference' => Yii::t('common', 'Notification Preference'),
        ];
    }
    public static function getOrderStatus()
    {
        return [
            0 => Yii::t('common', 'Active'),
            1 => Yii::t('common', 'Inactive'),
            2 => Yii::t('common', 'pending'),
            3 => Yii::t('common', 'Cancelled'),


        ];
    }

    public static function getPaymentStatus()
    {
        return [
            0 => Yii::t('common', 'Partial Payment'),
            1 => Yii::t('common', 'Full Payment'),
            2 => Yii::t('common', 'No Payment'),
            3 => Yii::t('common', 'Refunded'),

        ];
    }
    public function getPaymentsStores()
{
    return $this->hasMany(PaymentsStore::className(), ['order_id' => 'id']);
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
     * Gets query for [[OrderDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetails::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     *
     * @return bool Whether the deletion was successful.
     */
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
    public function updateOrder()
    {
        // Start a database transaction
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Calculate total price before tax
            $totalPriceBeforeTax = 0;
            foreach ($this->orderDetails as $detail) {
                $totalPriceBeforeTax += $detail->price * $detail->quantity;
            }

            // Set the price before tax
            $this->price = $totalPriceBeforeTax;

            // Get the tax rate (assumed to be a method or a service that calculates VAT)
            $taxRate = SubscriptionHelper::instance()->VATCalculator($this->academy_id);
            $this->tax_value = $this->price * $taxRate;

            // Calculate the total price including tax
            $this->total_price = $this->price + $this->tax_value;

            // Calculate the remaining amount
            $this->remaining_amount = $this->total_price - $this->amount_paid;

            // Save the order with updated values
            if (!$this->save(false, [
                'price',
                'tax_value',
                'total_price',
                'remaining_amount'
            ])) {
                throw new \Exception('Failed to save order.');
            }

            // Commit the transaction
            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            // Rollback the transaction in case of failure
            $transaction->rollBack();
            Yii::error("Failed to update order: " . $e->getMessage());
            return false;
        }
    }
    public function updateOrderStatus()
{
    $transaction = Yii::$app->db->beginTransaction();

    try {
        $totalPriceBeforeTax = 0;
        foreach ($this->orderDetails as $detail) {
            $totalPriceBeforeTax += $detail->price * $detail->quantity;
        }
        $this->price = $totalPriceBeforeTax;

        $taxRate = SubscriptionHelper::instance()->VATCalculator($this->academy_id);
        $this->tax_value = $this->price * $taxRate;

        $this->total_price = $this->price + $this->tax_value;

        $this->remaining_amount = $this->total_price - $this->amount_paid;

        $this->payment_status = ($this->remaining_amount <= 0) ? Orders::STATUS_ACTIVE : Orders::STATUS_ACTIVE;  

        $this->order_status = ($this->remaining_amount <= 0) ? Orders::PARTIAL_PAYMENT : Orders::NO_PAYMENT;

        if (!$this->save(false, ['price', 'tax_value', 'total_price', 'remaining_amount', 'payment_status', 'order_status'])) {
            throw new \Exception('Failed to update order.');
        }

        $transaction->commit();
        return true;

    } catch (\Exception $e) {
        $transaction->rollBack();
        Yii::error("Failed to update order: " . $e->getMessage());
        return false;
    }
}

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }
    public function getPaymentStatusText()
    {
        $statuses = self::getPaymentStatus();
        return $statuses[$this->payment_status] ?? '';
    }
}
