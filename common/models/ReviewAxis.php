<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "review_axis".
 *
 * @property int $id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $axis_id
 * @property int|null $status
 * @property string|null $description
 * @property float|null $value
 *
 * @property Axis $axis
 */
class ReviewAxis extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review_axis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'axis_id', 'status'], 'integer'],
            [['axis_id'], 'required'],
            [['description'], 'string'],
            [['value'], 'number'],
            
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['axis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Axis::class, 'targetAttribute' => ['axis_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'axis_id' => Yii::t('backend', 'Axis ID'),
            'status' => Yii::t('backend', 'Status'),
            'description' => Yii::t('backend', 'Description'),
            'value' => Yii::t('backend', 'Value'),
        ];
    }

    /**
     * Gets query for [[Axis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAxis()
    {
        return $this->hasOne(Axis::class, ['id' => 'axis_id']);
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
    public  function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }
    public function reviewAxaiStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">'.Yii::t('backend', 'Not Active').'</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">'.Yii::t('backend', 'Active').'</span>'
        ];
    }
    public static function getStatuses($controllerType)
    {
        $statuses = [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];
    
    
        return $statuses;
    }
   
}