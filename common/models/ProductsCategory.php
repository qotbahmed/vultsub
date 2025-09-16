<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "products_category".
 *
 * @property int $id
 * @property int $academy_id
 * @property string $title
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Academies $academy
 * @property Products[] $products
 */
class ProductsCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_id', 'title'], 'required'],
            [['academy_id', 'created_by', 'updated_by'], 'integer'],
          //  [['title'], 'string', 'max' => 100],
            ['title', 'match', 'pattern' => '/^[\p{L}][\p{L}0-9\s]{2,}$/u', 'message' => Yii::t('common', 'Title must start with a letter, contain at least 3 characters, and contain only letters, numbers, and spaces.')],

            [['updated_at', 'created_at'], 'string', 'max' => 255],
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
            'title' => Yii::t('common', 'Title'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_at' => Yii::t('common', 'Created At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademies()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }
    

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['category_id' => 'id']);
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
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
}
