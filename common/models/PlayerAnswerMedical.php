<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_answer_medical".
 *
 * @property int $id
 * @property int $question_id
 * @property string|null $answer
 * @property string|null $note
 * @property int $player_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $player
 * @property AcademyMedicalQuestions $question
 */
class PlayerAnswerMedical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_answer_medical';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'player_id'], 'required'],
            [['question_id', 'player_id', 'created_by', 'updated_by'], 'integer'],
            [['note'], 'string'],
            ['answer', 'safe'],
            ['answer', 'each', 'rule' => ['string'], 'when' => function ($model) {
                return is_array($model->answer);
            }],
            ['answer', 'trim'], 
            ['answer', 'validateAnswer'], 
            [['created_at', 'updated_at'], 'safe'],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademyMedicalQuestions::class, 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    
    public function validateAnswer($attribute, $params)
    {
        $value = $this->$attribute;
    
        if (is_array($value)) {
            $hasValidValue = array_filter($value, fn($item) => trim((string)$item) !== '');
            if (empty($hasValidValue)) {
                $this->addError($attribute, 'الإجابة يجب أن تحتوي على كلمات.');
            }
        } elseif (trim((string)$value) === '') {
            $this->addError($attribute, 'الإجابة يجب أن تحتوي على كلمات.');
        }
    }
public function getDecodedAnswer()
{
    if (is_array($this->answer)) {
        return $this->answer;
    }

    $decoded = json_decode($this->answer, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return $decoded;
    }

    return [$this->answer]; // fallback
}
   
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'question_id' => Yii::t('common', 'Question ID'),
            'answer' => Yii::t('common', 'Answer'),
            'note' => Yii::t('common', 'Note'),
            'player_id' => Yii::t('common', 'Player ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'player_id']);
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(AcademyMedicalQuestions::class, ['id' => 'question_id']);
    }
    // PlayerAnswerMedical.php

public function getAcademyQuestion()
{
    return $this->hasOne(AcademyMedicalQuestions::class, ['id' => 'question_id']);
}

public function getMedicalQuestion()
{
    return $this->hasOne(MedicalQuestions::class, ['id' => 'medical_id'])
                ->via('academyQuestion');
}


    public function beforeSave($insert)
    {
        if (is_array($this->answer)) {
            $this->answer = json_encode($this->answer); 
        }
        return parent::beforeSave($insert);
    }

   


}
