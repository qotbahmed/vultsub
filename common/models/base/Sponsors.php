<?php

namespace common\models\base;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\db\ActiveRecord;
use common\models\SponsorsQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "sponsors".
 *
 * @property integer $id
 * @property string $title
 * @property string $path
 * @property string $base_url
 * @property string $created_at
 * @property string $updated_at
 */
class Sponsors extends ActiveRecord
{
    public $image;

    use RelationTrait;


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
            [['title'], 'required'],
            [['image'], 'safe'],

            [['title', 'path', 'base_url', ], 'string', 'max' => 255],
           [['created_at', 'updated_at'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sponsors';
    }

    /**
     * 
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock 
     * 
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend','ID'),
            'title' =>  Yii::t('backend','Title'),
            'path' => Yii::t('backend', 'Path'),
            'base_url' => Yii::t('backend', 'Base Url'),
        ];
    }
    public function getImage($default = null)
    {
        return $this->path
            ? Yii::getAlias($this->base_url .'/'. $this->path)
            : $default;
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

            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
            ],

        ];
    }

    /**
     * @inheritdoc
     * @return SponsorsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SponsorsQuery(get_called_class());
    }
}
