<?php

namespace frontend\models\base;

use trntv\filekit\behaviors\UploadBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use backend\models\query\GalleryPhotosQuery;
use mootensai\relation\RelationTrait;

/**
 * This is the base model class for table "gallery_photos".
 *
 * @property integer $id
 * @property integer $gallery_id
 * @property string $path
 * @property string $base_url
 * @property string $type
 * @property integer $size
 * @property string $name
 * @property string $title
 * @property string $header_one
 * @property string $header_two
 * @property string $header_three
 * @property integer $has_more
 * @property string $url
 * @property string $heder_four
 * @property string $updated_at
 * @property integer $sort
 * @property string $created_at
 *
 * @property \backend\models\Gallery $gallery
 */
class GalleryPhotos extends ActiveRecord
{
    public $image;

    use RelationTrait;
    use MultiLanguageTrait;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_id'], 'required'],
            [['gallery_id', 'size', 'sort','has_more'], 'integer'],
            [['path', 'base_url', 'type', 'name', 'created_at', 'updated_at','title', 'header_one', 'header_two', 'header_three', 'url', 'heder_four'], 'string', 'max' => 255],
            [['image'],'safe'],

        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery_photos';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'gallery_id' => Yii::t('backend', 'Gallery ID'),
            'path' => Yii::t('backend', 'Path'),
            'base_url' => Yii::t('backend', 'Base Url'),
            'type' => Yii::t('backend', 'Type'),
            'size' => Yii::t('backend', 'Size'),
            'name' => Yii::t('backend', 'Name'),
            'title' => Yii::t('backend', 'Title'),
            'header_one' => Yii::t('backend', 'Header One'),
            'header_two' => Yii::t('backend', 'Header Two'),
            'header_three' => Yii::t('backend', 'Header Three'),
            'has_more' => Yii::t('backend', 'Has More'),
            'url' => Yii::t('backend', 'Url'),
            'heder_four' => Yii::t('backend', 'Header Four'),
            'sort' => Yii::t('backend', 'Sort'),
        ];
    }

    public function getSlideImage()
    {
        if ($this->path) {
            return $this->base_url .'/'. $this->path;
        } else {
            return '';
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(\backend\models\Gallery::className(), ['id' => 'gallery_id']);
    }

    public function behaviors()
    {
        return [
            'sortBehavior' => [
                'class' => 'demi\sort\SortBehavior',
                'sortConfig' => [
                    'sortAttribute' => 'sort',
                ]
            ],
            [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
            ],

           'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],

            'mlBehavior'=>[
                'class'    => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table'         => 'translations_with_text',
                    'attributes'       => ['title','name','header_one','header_two','header_three','heder_four'],  //,'min_age','start_every','study_time'
                    'admin_routes'     => [
                        'products/update',
                        'products/index',
                    ],
                ],
            ],

        ];
    }


    /**
     * @inheritdoc
     * @return GalleryPhotosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GalleryPhotosQuery(get_called_class());
    }
}
