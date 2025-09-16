<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription_discounts".
 *
 * @property int $id
 * @property int $promos_id
 * @property int $subscription_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $name
 * @property int $amount
 * @property string|null $discount_type
 * @property int|null $percentage
 * @property int|null $allow_stack
 *
 * @property Promos $promos
 * @property Subscription $subscription
 */
class SubscriptionDiscounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription_discounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promos_id', 'subscription_id', 'name', 'amount'], 'required'],
            [['promos_id', 'subscription_id', 'created_by', 'updated_by',  'percentage', 'allow_stack'], 'integer'],
            [['created_at', 'updated_at','amount'], 'safe'],
            [['name', 'discount_type'], 'string', 'max' => 255],
            [['promos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promos::class, 'targetAttribute' => ['promos_id' => 'id']],
            [['subscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::class, 'targetAttribute' => ['subscription_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'promos_id' => Yii::t('common', 'Promos ID'),
            'subscription_id' => Yii::t('common', 'Subscription ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'name' => Yii::t('common', 'Name'),
            'amount' => Yii::t('common', 'Amount'),
            'discount_type' => Yii::t('common', 'Discount Type'),
            'percentage' => Yii::t('common', 'Percentage'),
            'allow_stack' => Yii::t('common', 'Allow Stack'),
        ];
    }

    /**
     * Gets query for [[Promos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPromos()
    {
        return $this->hasOne(Promos::class, ['id' => 'promos_id']);
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
}
