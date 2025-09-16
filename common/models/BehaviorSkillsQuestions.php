<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "behavior_skills_questions".
 *
 * @property int $id
 * @property string|null $questions
 * @property int|null $answer
 * @property string|null $note
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Academies $academy
 */
class BehaviorSkillsQuestions extends \yii\db\ActiveRecord
{
    const ANSWER_EXCELLENT = 5;
    const ANSWER_VERY_GOOD = 4;
    const ANSWER_GOOD = 3;
    const ANSWER_ACCEPTABLE = 2;
    const ANSWER_POOR = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'behavior_skills_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer', 'created_by', 'updated_by'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['questions'], 'string', 'max' => 255],
            [['questions'], 'unique', 'message' => Yii::t('common', 'This question has already been taken.')], 

         
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'questions' => Yii::t('common', 'Questions'),
            'answer' => Yii::t('common', 'Answer'),
            'note' => Yii::t('common', 'Note'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
  
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
    public static function getAnswerOptions()
    {
        return [
            self::ANSWER_EXCELLENT => Yii::t('common', 'Excellent (5)'),
            self::ANSWER_VERY_GOOD => Yii::t('common', 'Very Good (4)'),
            self::ANSWER_GOOD => Yii::t('common', 'Good (3)'),
            self::ANSWER_ACCEPTABLE => Yii::t('common', 'Acceptable (2)'),
            self::ANSWER_POOR => Yii::t('common', 'Poor (1)'),
        ];
    }
    
}
