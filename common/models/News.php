<?php

namespace common\models;

use Yii;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $image_path
 * @property string|null $image_base_url
 * @property int|null $status
 * @property string|null $intro
 * @property string|null $created_at
 * @property int|null $academy_id
 *
 * @property Academies $academy
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    public function behaviors()
    {
        return [
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'image_path',
                'baseUrlAttribute' => 'image_base_url'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'intro'], 'required'],
            [['description'], 'string'],
            [['status', 'academy_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'intro'], 'string', 'max' => 100],
            [['image_path', 'image_base_url'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['image_path', 'image_base_url', 'image'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'image_path' => Yii::t('common', 'Image Path'),
            'image_base_url' => Yii::t('common', 'Image Base Url'),
            'status' => Yii::t('common', 'Active'),
            'created_at' => Yii::t('common', 'Created At'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'image' => Yii::t('common', 'Image'),
            'intro' => Yii::t('common', 'Short Introduction'),
            'description' => Yii::t('common', 'Full Description'),
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
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

public function attributeHints()
{
    return [
        'intro' => Yii::t('common', 'A brief summary (max 100 characters) displayed in news listings.'),
        'description' => Yii::t('common', 'Detailed content of the news (recommended 500-1000 words).'),
    ];
}
}
