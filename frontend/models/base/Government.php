<?php

namespace frontend\models\base;

use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\db\ActiveRecord;
use backend\models\query\GovernmentQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "government".
 *
 * @property integer $id
 * @property string $country_code
 * @property string $government_code
 * @property string $title
 * @property integer $sort
 *
 * @property \backend\models\Customer[] $customers
 */
class Government extends ActiveRecord
{
    use MultiLanguageTrait;


    use RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'title'], 'required'],
            [[ 'title'], 'string', 'max' => 255],
            [[ 'government_code', 'title'], 'string', 'max' => 255],
            [['sort','country_code'],'integer'],
            ['government_code','safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'government';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'country_code' => Yii::t('backend', 'Country Code'),
            'government_code' => Yii::t('backend', 'Government Code'),
            'title' => Yii::t('backend', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(\backend\models\Customer::className(), ['government_id' => 'id']);
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
                        'government/update',
                        'government/index',
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return GovernmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GovernmentQuery(get_called_class());
    }
}
