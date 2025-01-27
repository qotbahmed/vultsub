<?php

namespace backend\models\base;

use trntv\filekit\behaviors\UploadBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\db\ActiveRecord;
use backend\models\query\ContactQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "contact".
 *
 * @property integer $id
 * @property string $title
 * @property string $header_one
 * @property string $header_two
 * @property string $image_path
 * @property string $image_base_url
 * @property string $first_section_header
 * @property string $first_section_details
 * @property string $second_section_title
 * @property string $second_section_details
 * @property string $third_section_title
 * @property string $third_section_details
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Contact extends ActiveRecord
{

    use RelationTrait;
    use MultiLanguageTrait;

    public $image;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'header_one', 'header_two',  'first_section_header', 'first_section_details', 'second_section_title', 'second_section_details', 'third_section_title', 'third_section_details'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['title', 'header_one', 'header_two',  'first_section_header', 'first_section_details', 'second_section_title',
                'second_section_details', 'third_section_title', 'third_section_details', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['image_path', 'image_base_url'],'string'],
            ['image','safe']

        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'header_one' => Yii::t('backend', 'Header One'),
            'header_two' => Yii::t('backend', 'Header Two'),
            'image_path' => Yii::t('backend', 'Image  Path'),
            'image_base_url' => Yii::t('backend', 'Image Base Url'),
            'first_section_header' => Yii::t('backend', 'First Section Header'),
            'first_section_details' => Yii::t('backend', 'First Section Details'),
            'second_section_title' => Yii::t('backend', 'Second Section Header'),
            'second_section_details' => Yii::t('backend', 'Second Section Details'),
            'third_section_title' => Yii::t('backend', 'Third Section Header'),
            'third_section_details' => Yii::t('backend', 'Third Section Details'),
        ];
    }

    public function getLogoImage()
    {
        if ($this->image_path) {
            return $this->image_base_url .'/'. $this->image_path;
        } else {
            return "/img/contact.png";
        }
    }


    /**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'image_path',
                'baseUrlAttribute' => 'image_base_url',
            ],
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_text',
                    'attributes' => ['title', 'header_one','header_two','first_section_header','first_section_details','second_section_title'
                    ,'third_section_title','second_section_details','third_section_details'],
                    'admin_routes' => [
                        'contact/update',
                        'contact/index',
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return ContactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactQuery(get_called_class());
    }
}
