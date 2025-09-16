<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property int $academy_id
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

     const STATUS_NOT_ACTIVE = 0;
     const STATUS_ACTIVE = 1;
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question', 'answer'], 'required'],
            [['answer'], 'string'],
            [['academy_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['question'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'question' => Yii::t('common', 'Question'),
            'answer' => Yii::t('common', 'Answer'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Inactive'),
        ];
    }
    public function getStatusLabel()
    {
        return self::getStatusOptions()[$this->status] ?? Yii::t('common', 'Unknown');
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
