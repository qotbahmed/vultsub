<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "academy_medical_questions".
 *
 * @property int $id
 * @property int $medical_id
 * @property int $academy_id
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $updated_by
 *
 * @property Academies $academy
 * @property MedicalQuestions $medical
 */
class AcademyMedicalQuestions extends \yii\db\ActiveRecord
{
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academy_medical_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['medical_id', 'academy_id'], 'required'],
            [['medical_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['medical_id'], 'exist', 'skipOnError' => true, 'targetClass' => MedicalQuestions::class, 'targetAttribute' => ['medical_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['medical_id', 'academy_id'], 'unique', 'targetAttribute' => ['medical_id', 'academy_id'], 'message' => Yii::t('common','This question has already been taken.')], 


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'medical_id' => Yii::t('common', 'Medical ID'),
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
     * Gets query for [[Medical]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedical()
    {
        return $this->hasOne(MedicalQuestions::class, ['id' => 'medical_id']);
    }
    
    public function getPlayerAnswers()
    {
        return $this->hasMany(PlayerAnswerMedical::class, ['question_id' => 'id']);
    }

    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
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
    public function getAnswerOptions()
    {
        if ($this->question_type == self::TYPE_RADIO || $this->question_type == self::TYPE_CHECKBOX) {
            return json_decode($this->options, true); // Assuming 'options' column contains JSON-encoded options
        }
    
        return [];
    }
    

}
