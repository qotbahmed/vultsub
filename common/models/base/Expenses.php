<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\ExpensesQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "expenses".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $amount
 * @property integer $expenses_category_id
 * @property integer $main_category_id
 * @property string $academy_id
 * @property integer $academy_sport_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $exchange_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $treasury
 *
 * @property \common\models\ExpensesCategory $expensesCategory
 * @property \common\models\ManExpensesCategory $mainCategory
 * @property \common\models\Academies $academy
 * @property \common\models\AcademySport $academySport
 */
class Expenses extends ActiveRecord
{
    const TREASURY_CASH = 0;
    const TREASURY_BANK = 1;

    use RelationTrait;

    public $close = 0;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'expensesCategory',
            'mainCategory',
            'academy',
            'academySport'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'amount', 'expenses_category_id', 'main_category_id', 'academy_sport_id', 'receipt_number'], 'required'],
            [['description'], 'string'],
            [['amount'], 'number'],
            [['close',], 'safe'],
            [['expenses_category_id', 'main_category_id', 'academy_id', 'academy_sport_id', 'created_by', 'updated_by', 'receipt_number'], 'integer'],
            [['created_at', 'updated_at', 'date_expenses'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['academy_id', 'receipt_number'], 'unique',
            'targetAttribute' => ['academy_id', 'receipt_number'],
            'message' => Yii::t('common', 'This receipt number is already used in this academy.'),
           ],
            [
                'receipt_number',
                'integer',
                'min' => 1,
                'max' => 2147483647,
                'message' => Yii::t('common', 'Receipt number must be between 1 and 2147483647.')
            ],
            ['treasury', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Statement'),
            'date_expenses' => Yii::t('backend', 'Expenses date'),
            'exchange_date' => Yii::t('backend', 'Exchange date'),
            'description' => Yii::t('backend', 'Details'),
            'amount' => Yii::t('backend', 'Amount'),
            'expenses_category_id' => Yii::t('backend', 'Expenses category'),
            'main_category_id' => Yii::t('common', 'Main Category'),
            'academy_id' => Yii::t('backend', 'Academy ID'),
            'created_at' => Yii::t('backend', 'Created At'),
            'academy_sport_id' => Yii::t('backend', 'Sport'),
            'receipt_number' => Yii::t('backend', 'Receipt Number'),
            'treasury' => Yii::t('backend', 'Treasury Type'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesCategory()
    {
        return $this->hasOne(\common\models\ExpensesCategory::className(), ['id' => 'expenses_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainCategory()
    {
        return $this->hasOne(\common\models\ManExpensesCategory::className(), ['id' => 'main_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(\common\models\Academies::className(), ['id' => 'academy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySport()
    {
        return $this->hasOne(\common\models\AcademySport::className(), ['id' => 'academy_sport_id']);
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
        ];
    }

    /**
     * @inheritdoc
     * @return ExpensesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExpensesQuery(get_called_class());
    }
    public static function getTreasuryTypes()
{
    return [
        self::TREASURY_CASH => Yii::t('backend', 'Cash Treasury'),
        self::TREASURY_BANK => Yii::t('backend', 'Bank Treasury'),
    ];
}
public function statuses()
{
    return [
        self::TREASURY_CASH => Yii::t('common', 'cash'),
        self::TREASURY_BANK => Yii::t('common', 'back'),
    ];
}
public function getTreasuryLabel()
{
    $types = self::getTreasuryTypes();
    return isset($types[$this->treasury]) ? $types[$this->treasury] : null;
}

}
