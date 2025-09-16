<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_review".
 *
 * @property int $id
 * @property int|null $status
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property int|null $created_by
 * @property int $user_id
 * @property int $academy_id
 * @property int $axis_id
 * @property float|null $total_rating
 *
 * @property Academies $academy
 * @property Axis $axis
 * @property User $user
 * @property string|null $comment

 */
class UserReview extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'updated_by', 'created_by', 'user_id', 'academy_id', 'axis_id'], 'integer'],
            [['user_id', 'academy_id', 'axis_id'], 'required'],
            [['updated_at', 'created_at'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['axis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Axis::class, 'targetAttribute' => ['axis_id' => 'id']],
            [['total_rating'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'status' => Yii::t('backend', 'Status'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_by' => Yii::t('backend', 'Created By'),
            'user_id' => Yii::t('backend', 'User ID'),
            'academy_id' => Yii::t('backend', 'Academy ID'),
            'axis_id' => Yii::t('backend', 'Axis ID'),
            'total_rating' => Yii::t('backend', 'Total Rating'),
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

    /**
     * Gets query for [[Axis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAxis()
    {
        return $this->hasOne(Axis::class, ['id' => 'axis_id']);
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
public function userReviewStatuses()
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