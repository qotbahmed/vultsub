<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "academy_behavior_skills_questions".
 *
 * @property int $id
 * @property int $behavior_id
 * @property int $academy_id
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $updated_by
 *
 * @property Academies $academy
 * @property BehaviorSkillsQuestions $behavior0
 */
class AcademyBehaviorSkillsQuestions extends \yii\db\ActiveRecord
{
    public $questions; 

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
        return 'academy_behavior_skills_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['behavior_id', 'academy_id'], 'required'],
            [['behavior_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['behavior_id'], 'exist', 'skipOnError' => true, 'targetClass' => BehaviorSkillsQuestions::class, 'targetAttribute' => ['behavior_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['behavior_id', 'academy_id'], 'unique', 'targetAttribute' => ['behavior_id', 'academy_id'], 'message' => Yii::t('common','This question has already been taken.')], 
         



        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'behavior_id' => Yii::t('common', 'Behavior ID'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Academy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    /**
     * Gets query for [[Behavior0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBehavior0()
    {
        return $this->hasOne(BehaviorSkillsQuestions::class, ['id' => 'behavior_id']);
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
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
    public function getQuestions()
{
    return $this->hasMany(BehaviorSkillsQuestions::class, ['academy_id' => 'academy_id']);
}
public function deleteWithRelated()
{
    $transaction = Yii::$app->db->beginTransaction();
    try {
        // Add additional deletion logic here, if necessary.

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
