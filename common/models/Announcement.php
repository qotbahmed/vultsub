<?php

namespace common\models;
use trntv\filekit\behaviors\UploadBehavior;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

use Yii;

/**
 * This is the model class for table "announcement".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int $academy_id
 * @property string|null $logo_base_url
 * @property string|null $logo_path
 *
 * @property Academies $academy
 */
class Announcement extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    public $image;
    use RelationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'announcement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'academy_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['created_at', 'updated_at','image'], 'safe'],

            [['academy_id'], 'required'],
            [['title', 'logo_base_url', 'logo_path'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
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
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'start_date' => Yii::t('backend', 'Start Date'),
            'end_date' => Yii::t('backend', 'End Date'),
            'academy_id' => Yii::t('backend', 'Academy ID'),
            'logo_base_url' => Yii::t('backend', 'Logo Base Url'),
            'logo_path' => Yii::t('backend', 'Logo Path'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(), 
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'logo_path',
                'baseUrlAttribute' => 'logo_base_url'
            ],
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
     * Loads the model with given data.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model and related models if any.
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
    public function getLogo($default = '')
{
    return $this->logo_path
        ? rtrim(Yii::getAlias($this->logo_base_url), '/') . '/' . ltrim(Yii::getAlias($this->logo_path), '/')
        : $default;
}
public  function statuses()
{
    return [
        self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
        self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
    ];
}
public function announcementStatuses()
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
