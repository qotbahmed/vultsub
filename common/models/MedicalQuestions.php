<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "medical_questions".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $created_by
 * @property string|null $questions
 * @property int|null $question_type
 * @property string|null $options
 */
class MedicalQuestions extends \yii\db\ActiveRecord
{
    const TYPE_TEXT = 1;
    const TYPE_RADIO = 2;
    const TYPE_CHECKBOX = 3;
    const TYPE_NUMBER = 4;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medical_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['updated_by', 'created_by', 'question_type'], 'integer'],
            [['questions', 'options'], 'string', 'max' => 1000],
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
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_by' => Yii::t('common', 'Created By'),
            'questions' => Yii::t('common', 'Questions'),
            'question_type' => Yii::t('common', 'Question Type'),
            'options' => Yii::t('common', 'Options'),
        ];
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
    public static function getQuestionTypes()
    {
        return [
            self::TYPE_TEXT => Yii::t('common', 'Text'),
            self::TYPE_RADIO => Yii::t('common', 'Radio (Single Choice)'),
            self::TYPE_CHECKBOX => Yii::t('common', 'Checkbox (Multiple Choices)'),
            self::TYPE_NUMBER => Yii::t('common', 'Number'),
        ];
    }
 
 
    public function getAnswerOptions()
{
    if (in_array($this->question_type, [self::TYPE_RADIO, self::TYPE_CHECKBOX])) {
        $options = explode(',', $this->options);
        
        return array_map(function($option) {
            return Yii::t('common', trim($option));
        }, $options);
    }

    return [];
}
public function deleteWithRelated()
{
    $transaction = Yii::$app->db->beginTransaction();
    try {
        if ($this->delete() === false) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    } catch (\Exception $e) {
        $transaction->rollBack();
        throw $e;
    }
}

}
