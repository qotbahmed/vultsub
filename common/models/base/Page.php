<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\PageQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use trntv\filekit\behaviors\UploadBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;

/**
 * This is the base model class for table "page".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $view
 * @property string $image_base_url
 * @property string $image_path
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Page extends ActiveRecord
{
    public $image;

    use MultiLanguageTrait;
    use RelationTrait;

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            ''
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'body', 'status'], 'required'],
            [['body'], 'string'],
            [['status'], 'integer'],
            [['slug', 'title', 'view', 'image_base_url', 'image_path'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 200],
            ['image', 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'slug' => Yii::t('backend', 'Slug'),
            'title' => Yii::t('backend', 'Title'),
            'body' => Yii::t('backend', 'Body'),
            'view' => Yii::t('backend', 'View'),
            'image_base_url' => Yii::t('backend', 'Image Base Url'),
            'image_path' => Yii::t('backend', 'Image Path'),
            'status' => Yii::t('backend', 'Status'),
        ];
    }

/**
     * @inheritdoc
     * @return array
     */ 
    

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],

            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_text',
                    'attributes' => ['title','body'],
                    'admin_routes' => [
                        'page/update',
                        'page/index',
                    ],
                ],
            ],
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true
            ],            
            [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'image_path',
                'baseUrlAttribute' => 'image_base_url',
            ],
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DRAFT => Yii::t('common', 'Draft'),
            self::STATUS_PUBLISHED => Yii::t('common', 'Published'),
        ];
    }

    /**
     * @inheritdoc
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }

    public function getPageImage($default = null)
    {
        return $this->image_path
            ? Yii::getAlias($this->image_base_url  .'/'. $this->image_path)
            : $default;
    }
    
}
