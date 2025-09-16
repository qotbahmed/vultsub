<?php

namespace frontend\models\base;

use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\db\ActiveRecord;
use backend\models\query\CityQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "city".
 *
 * @property integer $id
 * @property integer $government_id
 * @property string $title
 * @property integer $sort
 *
 * @property \backend\models\Customer[] $customers
 */
class City extends ActiveRecord
{
    use MultiLanguageTrait;

    use RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['government_id','sort'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'government_id' => Yii::t('backend', 'Government ID'),
            'title' => Yii::t('backend', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(\backend\models\Customer::className(), ['city_id' => 'id']);
    }

/**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
           'sortBehavior' => [
                'class' => 'demi\sort\SortBehavior',
                'sortConfig' => [
                    'sortAttribute' => 'sort',
                ]
            ],
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_text',
                    'attributes' => ['title'],
                    'admin_routes' => [
                        'city/update',
                        'city/index',
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return CityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CityQuery(get_called_class());
    }
}
