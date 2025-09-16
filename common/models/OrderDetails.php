<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_details".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $produect_name
 * @property string $sport_name
 * @property float $price
 * @property int $quantity
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Orders $order
 * @property Products $product
 */
class OrderDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'produect_name', 'sport_name', 'price', 'quantity'], 'required'],
            [['order_id', 'product_id', 'quantity', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['produect_name', 'sport_name'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'order_id' => Yii::t('common', 'Order ID'),
            'product_id' => Yii::t('common', 'Product ID'),
            'produect_name' => Yii::t('common', 'Produect Name'),
            'sport_name' => Yii::t('common', 'Sport Name'),
            'price' => Yii::t('common', 'Price'),
            'quantity' => Yii::t('common', 'Quantity'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    public static function isProductInOrder($orderId, $productId)
    {
        return self::find()->where(['order_id' => $orderId, 'product_id' => $productId])->exists();
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

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $product = $this->product;

        if ($product !== null) {
            $quantityDifference = $this->quantity;

            if (!$insert) {
                if (isset($changedAttributes['quantity'])) {
                    $quantityDifference = $this->quantity - $changedAttributes['quantity'];
                }
            }

            $product->quantity_remaining -= $quantityDifference;

            if ($product->quantity_remaining < 0) {
                $product->quantity_remaining = 0;
            }

            $product->quantity_used += $quantityDifference;

            $product->save(false);
        }
    }
    public function afterDelete()
    {
        parent::afterDelete();

        $product = $this->product;

        if ($product !== null) {
            $product->quantity_remaining += $this->quantity;

            $product->quantity_used -= $this->quantity;

            if ($product->quantity_used < 0) {
                $product->quantity_used = 0;
            }

            $product->save(false);
        }
    }

    public static function productCountInOrder($orderId, $productId)
    {
        return self::find()
            ->where(['order_id' => $orderId, 'product_id' => $productId])
            ->sum('quantity') ?: 0;
    }
}
