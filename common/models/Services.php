<?php

namespace common\models;
use trntv\filekit\behaviors\UploadBehavior;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property string|null $img
 * @property string|null $img_path
 * @property string|null $img_base_url
 * @property int|null $status
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property ServicesCategory $category
 */
class Services extends \yii\db\ActiveRecord
{  
      const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }
    public function behaviors()
    {
        return [
            'fileUploadBehavior' => [
                'class' => UploadBehavior::class,
                'attribute' => 'img',
                'pathAttribute' => 'img_path',
                'baseUrlAttribute' => 'img_base_url',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'name'], 'required'],
            [['category_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'img'], 'safe'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_NOT_ACTIVE]],
            [['name'], 'string', 'max' => 255],
            [['img_path', 'img_base_url'], 'string', 'max' => 500],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServicesCategory::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'category_id' => Yii::t('common', 'Category ID'),
            'name' => Yii::t('common', 'Name'),
            'description' => Yii::t('common', 'Description'),
            'img' => Yii::t('common', 'Img'),
            'img_path' => Yii::t('common', 'Img Path'),
            'img_base_url' => Yii::t('common', 'Img Base Url'),
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
    public function getservicesPictureUrl()
{
    if ($this->img_path && $this->img_base_url) {
        return $this->img_base_url . '/' . $this->img_path;
    }
    return null; 
}
    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicesCategory()
    {
        return $this->hasOne(ServicesCategory::class, ['id' => 'category_id']);
    }
}
