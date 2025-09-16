<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "age_group".
 *
 * @property int $id
 * @property string|null $group_name
 * @property string|null $group_name_en
 * @property int|null $from
 * @property int|null $to
 * @property int|null $academy_id
 *
 * @property Academies $academy
 */
class AgeGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'age_group';
    }

 /**
 * {@inheritdoc}
 */
public function rules()
{
    return [
        [['from', 'to', 'academy_id', 'group_name'], 'required'], 
        [['from', 'to', 'academy_id'], 'integer', 'min' => 1],    
        [['group_name', 'group_name_en'], 'string', 'max' => 255],                 
        [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
        ['from', 'validateFromTo'], 
    ];
}

/**
 * Custom validation function to ensure 'from' is less than 'to' and they are not equal.
 */
public function validateFromTo($attribute, $params)
{
    if ($this->from == $this->to) {
        $this->addError('from', Yii::t('common', 'The "from" and "to" fields cannot have the same value.'));
    }

    if ($this->from > $this->to) {
        $this->addError('from', Yii::t('common', 'The "from" value must be less than the "to" value.'));
    }
}




    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'group_name' => Yii::t('common', 'Group Name'),
            'group_name_en' => Yii::t('common', 'English Group Name'),
            'from' => Yii::t('common', 'From'),
            'to' => Yii::t('common', 'To'),
            'academy_id' => Yii::t('common', 'Academy ID'),
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
     * Get the translated group name based on current language after finding the record
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Use the English group name if the current language is English and the group_name_en field is not empty
        $currentLang = Yii::$app->language;
        if ($currentLang === 'en' && !empty($this->group_name_en)) {
            $this->group_name = $this->group_name_en;
        }
        // For Arabic (or any other language), we keep using the default 'group_name' field
    }
    public function loadAll($data)
    {
        return $this->load($data);
    }
    
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
    

    /**
     * Deletes the model and related models if any.
     * @return bool Whether the deletion was successful.
     */
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
