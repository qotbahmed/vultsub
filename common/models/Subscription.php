<?php

namespace common\models;

use api\helpers\SubscriptionHelper;
use Yii;
use common\models\Academies;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $academy_id
 * @property int|null $renewed_id
 * @property float|null $price_before_discount
 * @property float|null $price_after_discount
 * @property float|null $tax_value
 * @property float|null $total_price
 * @property float|null $amount_paid
 * @property float|null $remaining_amount
 * @property int|null $payment_status
 * @property int|null $subscription_status
 * @property float|null $discount
 * @property string $start_date
 * @property string $end_date
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $receipt
 *
 * @property UserProfile $parent
 * @property User $parentUser
 * @property SubscriptionDetails[] $subscriptionDetails
 * @property SubscriptionDiscounts[] $subscriptionDiscounts
 */
class Subscription extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_EXPIRED = 2;
    const STATUS_NEAR_EXPIRY = 3;
    const STATUS_PENDING = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_RENEWED_WITHOUT_PAYMENT = 6;
    const STATUS_FULL_PAYMENT = 1;
    const STATUS_PARTIAL_PAYMENT = 0;
    const STATUS_NO_PAYMENT = 2;
    const STATUS_REFUNDED = 3;
    public $payment_method;  // Add this line if the column is virtual or not part of the table schema


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'start_date', 'end_date'], 'required'],
            [['parent_id', 'payment_status', 'subscription_status', 'created_by', 'updated_by', 'academy_id'], 'integer'],
            [['price_before_discount', 'tax_value', 'total_price', 'amount_paid', 'remaining_amount', 'discount', 'receipt'], 'number'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'price_after_discount'], 'safe'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserProfile::class, 'targetAttribute' => ['parent_id' => 'user_id']],
            [['notification_preference'], 'boolean'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'parent_id' => Yii::t('common', 'Parent ID'),
            'academy_id' => Yii::t('common', 'Academy'),
            'price_before_discount' => Yii::t('common', 'Price Before Discount'),
            'price_after_discount' => Yii::t('common', 'Price After Discount'),
            'tax_value' => Yii::t('common', 'Tax Value'),
            'total_price' => Yii::t('common', 'Total Pricee'),
            'amount_paid' => Yii::t('common', 'Amount Paid'),
            'remaining_amount' => Yii::t('common', 'Remaining Amount'),
            'payment_status' => Yii::t('common', 'Payment Status'),
            'subscription_status' => Yii::t('common', 'Subscription Status'),
            'discount' => Yii::t('common', 'Discount'),
            'start_date' => Yii::t('common', 'Start Date'),
            'end_date' => Yii::t('common', 'End Date'),
            'suspend_start_date' => Yii::t('common', 'Suspend Start Date'),
            'suspend_end_date' => Yii::t('common', 'Suspend End Date'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'comment' => Yii::t('common', 'Comment'),
            'notification_preference' => Yii::t('common', 'Notification Preference'),
            'receipt' => Yii::t('common', 'Receipt'),

        ];
    }

    public static function getSubscriptionStatus()
    {
        return [
            0 => Yii::t('common', 'Active'),
            1 => Yii::t('common', 'Inactive'),
            2 => Yii::t('common', 'Expired'),
            3 => Yii::t('common', 'Near Expiry'),
            4 => Yii::t('common', 'pending'),
            5 => Yii::t('common', 'Cancelled'),
            6 => Yii::t('common', 'Renewed Without Payment'),


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

    /**
     * Gets query for [[SubscriptionDiscounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionDiscounts()
    {
        return $this->hasMany(SubscriptionDiscounts::class, ['subscription_id' => 'id']);
    }

    public function loadAll($data)
    {
        return $this->load($data);
    }

    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
    }


    /**
     * Deletes the model and related models if any.
     * @return bool Whether the deletion was successful.
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add additional deletion logic here, if necessary.
            SchedulesPlayer::deleteAll(['subscription_id' => $this->id]);

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


    public function getParentNamea()
    {
        $userProfile = $this->user->userProfile;
    }

    public function calculateTotalPrice()
    {
        $priceBeforeDiscount = 0;
        foreach ($this->subscriptionDetails as $detail) {
            $priceBeforeDiscount += $detail->packages->amount;
        }

        $this->price_before_discount = $priceBeforeDiscount;
        $this->price_after_discount = $priceBeforeDiscount - $this->discount;

        if ($this->price_after_discount < 0) {
            $this->price_after_discount = 0;
        }


        $taxRate = SubscriptionHelper::instance()->VATCalculator($this->academy_id);
        $this->tax_value = $this->price_after_discount * $taxRate;
        $this->total_price = $this->price_after_discount + $this->tax_value;
        $this->remaining_amount = $this->total_price - $this->amount_paid;
        $this->save();
    }


    public function updateSubscription()
    {
        $this->calculateTotalPrice();

        $this->remaining_amount = $this->total_price;

        return $this->save(false, [
            'price_before_discount',
            'price_after_discount',
            'tax_value',
            'total_price',
            'remaining_amount'
        ]);
    }


    // Assuming Subscription model has a parent_id attribute

    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['parent_id' => 'parent_id']);
    }

    public function getParent()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'parent_id']);
    }    public function getParentUser()
    {
        return $this->hasOne(User::class, ['id' => 'parent_id']);
    }

    /**
     * Get the description of the subscription status.
     *
     * @return string
     */
    public function getStatusDescription()
    {
        $statuses = [
            0 => 'Inactive',
            1 => 'Active',
            3 => 'Expired',
        ];

        return $statuses[$this->subscription_status] ?? 'Unknown';
    }

    public function suspend()
    {
        $this->subscription_status = self::STATUS_STOPPED;
        return $this->save(false, ['subscription_status']);
    }
public function getParentFullName()
{
    if ($this->parent) {
        return trim(($this->parent->firstname ?? '') . ' ' . ($this->parent->lastname ?? ''));
    }
    return null;
}

    public function activate()
    {
        $this->subscription_status = self::STATUS_ACTIVE;
        return $this->save(false, ['subscription_status']);
    }

    public function getParentName()
    {
        $userProfile = $this->parent; // Use the `getParent()` relationship
        return $userProfile ? $userProfile->full_name : Yii::t('common', 'Unknown Parent');
    }
    public function getParentNamee()
    {
        $userProfile = $this->parent; // Use the `getParent()` relationship
        return $userProfile ? $userProfile->firstname : Yii::t('common', 'Unknown Parent'). ' ' . $userProfile->lastname;
    }

    public function getSubscriptionDetails()
    {
        return $this->hasMany(SubscriptionDetails::class, ['subscription_id' => 'id']);
    }

    public function getParentProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'parent_id']);
    }


    /**
     * Get the count of discounts applied to this subscription.
     *
     * @return int
     */
    public function getDiscountsCount()
    {
        return $this->getSubscriptionDiscounts()->count();
    }

    public function getUniquePlayersCount()
    {
        return $this->getSubscriptionDetails()
            ->select('player_id')  // Select the player_id column
            ->distinct()
            ->count();
    }

    /**
     * Get the count of packages subscribed to this subscription.
     *
     * @return int
     */
    public function getPackagesCount()
    {
        return $this->getSubscriptionDetails()->count();
    }

    ///////////////////////////////////////////////////////////////
    public function getPaymentStatusName()
    {
        $statuses = self::getPaymentStatus();
        return isset($statuses[$this->payment_status]) ? $statuses[$this->payment_status] : 'Unknown';
    }

    /////////////////////////////////////////////////////////////
    public function getSubscriptionStatusName()
    {
        $statuses = self::getSubscriptionStatus();
        return isset($statuses[$this->subscription_status]) ? $statuses[$this->subscription_status] : 'Unknown';
    }


    public function getPayment()
    {
        return $this->hasOne(Payments::className(), ['id' => 'payment_method_id']);
    }

    public function getPayments()
    {
        return $this->hasMany(Payments::class, ['subscription_id' => 'id']);
    }


    public function getRefundedAmount()
    {
        return Payments::find()
            ->where(['subscription_id' => $this->id])
            ->andWhere(['<', 'amount', 0]) // Filter for negative amounts (refunds)
            ->sum('amount');
    }


    public function checkAndUpdateSubscriptionStatus()
    {
        if ($this->subscription_status === self::STATUS_CANCELLED) {
            Yii::info("Subscription is cancelled, no changes allowed.", __METHOD__);
            return false;
        }
        if ($this->subscription_status === self::STATUS_PENDING || $this->subscription_status === self::STATUS_RENEWED_WITHOUT_PAYMENT) {
            if ($this->payment_status == 2) {
                Yii::info("Subscription is pending and payment status is 'No Payment', keeping status as Pending.", __METHOD__);
                return false;
            } elseif (in_array($this->payment_status, [0, 1])) {
                $this->subscription_status = self::STATUS_ACTIVE;
                Yii::info("Partial or full payment made, updating status to Active", __METHOD__);
                return $this->save(false, ['subscription_status']);
            }
        }
        $currentDate = new \DateTime();
        $endDate = new \DateTime($this->end_date);
        $interval = $currentDate->diff($endDate);
        $daysRemaining = (int) $interval->days;
        $isExpired = $currentDate > $endDate;

        Yii::info("Current Date: " . $currentDate->format('Y-m-d'), __METHOD__);
        Yii::info("End Date: " . $endDate->format('Y-m-d'), __METHOD__);
        Yii::info("Days Remaining: " . $daysRemaining, __METHOD__);
        Yii::info("Is Expired: " . ($isExpired ? 'Yes' : 'No'), __METHOD__);

        if (in_array($this->subscription_status, [self::STATUS_INACTIVE])) {
            Yii::info("Subscription manually set to Active or Inactive, skipping automatic update.", __METHOD__);
            return false;
        }

        if ($isExpired) {
            if ($this->subscription_status !== self::STATUS_EXPIRED) {
                $this->subscription_status = self::STATUS_EXPIRED;
                Yii::info("Updating status to Expired", __METHOD__);
                return $this->save(false, ['subscription_status']);
            }
            return false;
        }

        if ($daysRemaining <= 7 && $this->subscription_status !== self::STATUS_NEAR_EXPIRY) {
            $this->subscription_status = self::STATUS_NEAR_EXPIRY;
            Yii::info("Updating status to Near Expiry", __METHOD__);
            return $this->save(false, ['subscription_status']);
        }

        if ($this->subscription_status !== self::STATUS_ACTIVE && !$isExpired && $daysRemaining > 7) {
            $this->subscription_status = self::STATUS_ACTIVE;
            Yii::info("Updating status to Active", __METHOD__);
            return $this->save(false, ['subscription_status']);
        }

        Yii::info("No update needed", __METHOD__);
        return false;
    }



    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    public function getPaymentRecord()
    {
        return $this->hasOne(Payments::class, ['subscription_id' => 'id']);
    }


    public function getBillStatus()
    {
        $isActive = in_array($this->subscription_status, [self::STATUS_ACTIVE, self::STATUS_NEAR_EXPIRY]);

        if (!$isActive && $this->amount_paid == 0) {
            return ['label' => Yii::t('common', 'Suspended and Unpaid'), 'class' => 'text-danger'];
        }

        if (!$isActive) {
            return ['label' => Yii::t('common', 'Suspended Subscription'), 'class' => 'text-warning'];
        }

        if ($isActive && $this->amount_paid == 0) {
            return ['label' => Yii::t('common', 'No Payment'), 'class' => 'text-danger'];
        }

        return null;
    }

    public function getDistinctPackagesWithCountAndAmount()
    {
        return SubscriptionDetails::find()
            ->select(['package_name', 'COUNT(*) as repeated_count', 'amount'])
            ->where(['subscription_id' => $this->id])
            ->groupBy(['package_name', 'amount'])
            ->asArray()
            ->all();
    }
    public function getLatestPayment()
    {
        return $this->hasOne(Payments::class, ['subscription_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    public function getOriginalSubscription()
    {
        return $this->hasOne(Subscription::class, ['id' => 'renewed_id']);
    }

    public function getRenewedSubscriptions()
    {
        return $this->hasMany(Subscription::class, ['renewed_id' => 'id']);
    }

    public static function getPaymentStatuss()
    {
        return [
            self::STATUS_PARTIAL_PAYMENT => Yii::t('common', 'Partial Payment'),
            self::STATUS_FULL_PAYMENT => Yii::t('common', 'Full Payment'),
            self::STATUS_NO_PAYMENT => Yii::t('common', 'No Payment'),
            self::STATUS_REFUNDED => Yii::t('common', 'Refunded'),

        ];
    }

    public function getPlayerUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'player_id'])
            ->via('subscriptionDetails');
    }

    public function getPlayerFirstName()
    {
        return $this->playerUserProfile ? $this->playerUserProfile->firstname : Yii::t('common', 'Unknown');
    }

    public function getPlayerLastName()
    {
        return $this->parentUser ? $this->parentUser->userProfile->firstname : Yii::t('common', 'Unknown');
    }

    /**
     * Determine if a given player_id represents the parent of this subscription.
     *
     * @param int $playerId
     * @return bool
     */
    public function isParentPlayerId($playerId)
    {
        return (int)$playerId === (int)$this->parent_id;
    }

    /**
     * Convenience method to get all players (children) under this subscription's parent
     * including the parent himself as the first element if found.
     *
     * @return User[]
     */
    public function getPlayersIncludingParent()
    {
        $players = User::find()->where(['parent_id' => $this->parent_id, 'user_type' => 1])->all();
        $parent = User::findOne($this->parent_id);
        return $parent ? array_merge([$parent], $players) : $players;
    }
    
}
