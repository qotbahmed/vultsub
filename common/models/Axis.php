<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "axis".
 *
 * @property int $id
 * @property string $title
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $sort
 * @property int|null $status
 *
 * @property ReviewAxis[] $reviewAxes
 * @property UserReview[] $userReviews
 */
class Axis extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'axis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_by', 'updated_by', 'status'], 'integer'],
            [['sort'], 'string'],
            [['title'], 'string', 'max' => 100],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
     public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
        ];
    }

    /**
     * Gets query for [[ReviewAxes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewAxes()
    {
        return $this->hasMany(ReviewAxis::class, ['axis_id' => 'id']);
    }

    /**
     * Gets query for [[UserReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserReviews()
    {
        return $this->hasMany(UserReview::class, ['axis_id' => 'id']);
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
public function axisStatuses()
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
