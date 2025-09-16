<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "talent_awards".
 *
 * @property int $id
 * @property int $talent_id
 * @property string $title
 * @property string|null $description
 * @property string $date
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TalentProfile $talent
 */
class TalentAwards extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'talent_awards';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'date'], 'required'],
            [['talent_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['talent_id'], 'exist', 'skipOnError' => true, 'targetClass' => TalentProfile::class, 'targetAttribute' => ['talent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'talent_id' => Yii::t('common', 'Talent id'),
            'title' => Yii::t('common', 'Title'),
            'description' => Yii::t('common', 'Description'),
            'date' => Yii::t('common', 'Date'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Talent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function gettalentProfile()
    {
        return $this->hasOne(TalentProfile::class, ['id' => 'talent_id']);
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time(); 
            }
            if ($this->isNewRecord) {
                $this->updated_at = time(); 
            }
            return true;
        }
        return false;
    }
    
    /**
     * Save model with validation
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
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
