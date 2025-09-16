<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\ExpensesCategoryQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "expenses_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $updated_at
 * @property string $created_at
 * @property string  $name_en
 * @property integer $created_by
 * @property integer $updated_by
 * @property int $academy_id
 * @property int $main_category_id
 * @property int $status
 * 
 *
 * @property \common\models\Expenses[] $expenses
 * @property \common\models\ManExpensesCategory $mainCategory
 */
class ExpensesCategory extends ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    use RelationTrait;

    public $close = 0;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'expenses',
            'mainCategory'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'academy_id'], 'required'],
            [['created_by', 'updated_by', 'academy_id', 'main_category_id'], 'integer'],
            [['close'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['updated_at', 'created_at', 'name_en'], 'string', 'max' => 255],
            [['status'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expenses_category';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name'),
            'academy_id' => Yii::t('backend', 'Academy ID'),
            'main_category_id' => Yii::t('backend', 'Main Category ID'),
            'name_en' => Yii::t('backend', 'English Title'),
        ];
    }

    /**
     * Magic method to handle property access
     * This ensures that when 'title' is accessed, it automatically returns the localized version
     * 
     * @param string $name Name of the property being accessed
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'name' && Yii::$app->language === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return parent::__get($name);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(\common\models\Expenses::className(), ['expenses_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainCategory()
    {
        return $this->hasOne(\common\models\ManExpensesCategory::className(), ['id' => 'main_category_id']);
    }

    /**
     * @inheritdoc
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
        ];
    }

    /**
     * @inheritdoc
     * @return ExpensesCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExpensesCategoryQuery(get_called_class());
    }
    public function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];
    }
}
