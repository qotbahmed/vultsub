<?php

namespace common\models\base;

use demi\sort\SortBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\db\ActiveRecord;
use common\models\query\FaqQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "faq".
 *
 * @property integer $id
 * @property string $question
 * @property string $answer
 * @property integer $sort
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property int $category_id
 * @property Category $category
 */
class Faq extends ActiveRecord
{

    use MultiLanguageTrait;

    use RelationTrait;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const sort_model_down = 3;

    const sort_model_up = 4;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'category'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer', 'category_id'], 'required'],
            [['answer'], 'string'],
            [['sort', 'category_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'status'], 'integer'],
            [['question'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'question' => Yii::t('backend', 'Question'),
            'answer' => Yii::t('backend', 'Answer'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'category_id' => Yii::t('backend', 'Category'),
        ];
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [

            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            // ...
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
                    'attributes' => ['question', 'answer'],
                    'admin_routes' => [
                        'faq/update',
                    ],
                ],
            ],


        ];
    }

//    public function changeSorting($direction)
//    {
//        ($direction == self::sort_model_up) ? $this->sort -= 1 : $this->sort +=  1;
//
//        if (!$this->save()) {
//            $errors = $this->getErrors();
//
//        }
//    }

    public function getCategory()
    {
        return $this->hasOne(\common\models\Category::className(), ['id' => 'category_id']);
    }


    /**
     * @inheritdoc
     * @return FaqQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FaqQuery(get_called_class());
    }


    public static function getStatuses()
    {

        return $statuses = [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">'.Yii::t('backend', 'Not Active').'</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">'.Yii::t('backend', 'Active').'</span>'
        ];


    }


}
