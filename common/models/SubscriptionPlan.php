<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "subscription_plans".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $branches_limit
 * @property int $students_limit
 * @property int $storage_limit_mb
 * @property float $price_monthly
 * @property float $price_yearly
 * @property int $is_active
 * @property int $sort_order
 * @property string $stripe_price_id_monthly
 * @property string $stripe_price_id_yearly
 * @property string $features
 * @property int $created_at
 * @property int $updated_at
 */
class SubscriptionPlan extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return 'subscription_plans';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['name', 'branches_limit', 'students_limit', 'storage_limit_mb', 'price_monthly', 'price_yearly'], 'required'],
            [['branches_limit', 'students_limit', 'storage_limit_mb', 'is_active', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['price_monthly', 'price_yearly'], 'number'],
            [['features'], 'string'],
            [['name', 'description', 'stripe_price_id_monthly', 'stripe_price_id_yearly'], 'string', 'max' => 255],
            [['is_active'], 'default', 'value' => self::STATUS_ACTIVE],
            [['sort_order'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'branches_limit' => 'Branches Limit',
            'students_limit' => 'Students Limit',
            'storage_limit_mb' => 'Storage Limit (MB)',
            'price_monthly' => 'Price Monthly',
            'price_yearly' => 'Price Yearly',
            'is_active' => 'Is Active',
            'sort_order' => 'Sort Order',
            'stripe_price_id_monthly' => 'Stripe Price ID Monthly',
            'stripe_price_id_yearly' => 'Stripe Price ID Yearly',
            'features' => 'Features',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getFeaturesArray()
    {
        return $this->features ? json_decode($this->features, true) : [];
    }

    public function setFeaturesArray($features)
    {
        $this->features = json_encode($features);
    }

    public function getPriceForPeriod($period)
    {
        return $period === 'yearly' ? $this->price_yearly : $this->price_monthly;
    }

    public function getStripePriceId($period)
    {
        return $period === 'yearly' ? $this->stripe_price_id_yearly : $this->stripe_price_id_monthly;
    }

    public function getYearlyDiscount()
    {
        if ($this->price_monthly > 0) {
            $yearlyEquivalent = $this->price_monthly * 12;
            return round((($yearlyEquivalent - $this->price_yearly) / $yearlyEquivalent) * 100);
        }
        return 0;
    }

    public static function getActivePlans()
    {
        return static::find()
            ->where(['is_active' => self::STATUS_ACTIVE])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();
    }
}
