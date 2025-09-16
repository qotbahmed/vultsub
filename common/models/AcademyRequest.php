<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "academy_requests".
 *
 * @property int $id
 * @property string $academy_name
 * @property string $manager_name
 * @property string $email
 * @property string $phone
 * @property string|null $address
 * @property string|null $city
 * @property int $branches_count
 * @property string|null $sports
 * @property string|null $description
 * @property string $status
 * @property int $requested_at
 * @property int|null $approved_at
 * @property int|null $rejected_at
 * @property string|null $notes
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $portal_academy_id
 * @property int|null $portal_user_id
 * @property int|null $user_id
 *
 * @property User $user
 */
class AcademyRequest extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academy_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_name', 'manager_name', 'email', 'phone'], 'required'],
            [['branches_count',  'approved_at', 'rejected_at', 'created_by', 'updated_by', 'portal_academy_id', 'portal_user_id', 'user_id'], 'integer'],
            [['address', 'description', 'notes'], 'string'],
            [['requested_at',], 'safe'],
            [['academy_name', 'manager_name', 'email', 'phone', 'city', 'sports'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_EXPIRED]],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'academy_name' => Yii::t('common', 'Academy Name'),
            'manager_name' => Yii::t('common', 'Manager Name'),
            'email' => Yii::t('common', 'Email'),
            'phone' => Yii::t('common', 'Phone'),
            'address' => Yii::t('common', 'Address'),
            'city' => Yii::t('common', 'City'),
            'branches_count' => Yii::t('common', 'Branches Count'),
            'sports' => Yii::t('common', 'Sports'),
            'description' => Yii::t('common', 'Description'),
            'status' => Yii::t('common', 'Status'),
            'requested_at' => Yii::t('common', 'Requested At'),
            'approved_at' => Yii::t('common', 'Approved At'),
            'rejected_at' => Yii::t('common', 'Rejected At'),
            'notes' => Yii::t('common', 'Notes'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'portal_academy_id' => Yii::t('common', 'Portal Academy ID'),
            'portal_user_id' => Yii::t('common', 'Portal User ID'),
            'user_id' => Yii::t('common', 'User ID'),
        ];
    }

    /**
     * Gets query for associated user.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get status options
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => Yii::t('common', 'Pending'),
            self::STATUS_APPROVED => Yii::t('common', 'Approved'),
            self::STATUS_REJECTED => Yii::t('common', 'Rejected'),
            self::STATUS_EXPIRED => Yii::t('common', 'Expired'),
        ];
    }

    /**
     * Get status label
     * @return string
     */
    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return $options[$this->status] ?? $this->status;
    }

    /**
     * Check if request is pending
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approve the request
     * @return bool
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_at = time();
        return $this->save();
    }

    /**
     * Reject the request
     * @return bool
     */
    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
        $this->rejected_at = time();
        return $this->save();
    }

    /**
     * Get sports as array
     * @return array
     */
    public function getSportsArray()
    {
        return $this->sports ? explode(',', $this->sports) : [];
    }

    /**
     * Set sports from array
     * @param array $sports
     */
    public function setSportsArray($sports)
    {
        $this->sports = implode(',', $sports);
    }
}
