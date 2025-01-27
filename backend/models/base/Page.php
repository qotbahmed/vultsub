<?php

namespace backend\models\base;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
/**
 * This is the base model class for table "page".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $view
 * @property string $image_base_path
 * @property string $image_path
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Page extends \yii\db\ActiveRecord
{
    use MultiLanguageTrait;
    use \mootensai\relation\RelationTrait;

    public $image;

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
            'view' => Yii::t('backend', 'Page For '),
            'image_base_path' => Yii::t('backend', 'Image Base Path'),
            'image_path' => Yii::t('backend', 'Image Path'),
            'status' => Yii::t('backend', 'Status'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['slug', 'title', 'body', 'view','image', 'image_base_url', 'image_path', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'image_path',
                'baseUrlAttribute' => 'image_base_url'
            ],
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_text',
                    'attributes' => ['title'],
                    'admin_routes' => [
                        'page/update',
                        'page/index',
                    ],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     * @return \backend\models\query\PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\query\PageQuery(get_called_class());
    }
}
