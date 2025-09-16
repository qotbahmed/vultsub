<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_answer_behavior".
 *
 * @property int $id
 * @property int $question_id
 * @property int|null $answer
 * @property string|null $note
 * @property int $player_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $player
 * @property AcademyBehaviorSkillsQuestions $question 

 */
class PlayerAnswerBehavior extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_answer_behavior';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'player_id'], 'required'],
            [['question_id', 'answer', 'player_id', 'created_by', 'updated_by'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademyBehaviorSkillsQuestions::class, 'targetAttribute' => ['question_id' => 'id']],

        ];
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
        return $this->hasOne(BehaviorSkillsQuestions::class, ['id' => 'question_id']);
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
    
}
