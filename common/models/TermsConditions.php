<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "terms_conditions".
 *
 * @property int $id
 * @property string $term
 * @property string $condition
 * @property int|null $status
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class TermsConditions extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'terms_conditions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['term', 'condition'], 'required'],
            [['condition'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_NOT_ACTIVE]],
            [['created_at', 'updated_at'], 'safe'],
            [['term'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'term' => Yii::t('common', 'Term'),
            'condition' => Yii::t('common', 'Condition'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Inactive'),
        ];
    }
    public function getStatusLabel()
    {
        return self::getStatusOptions()[$this->status] ?? Yii::t('common', 'Unknown');
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
