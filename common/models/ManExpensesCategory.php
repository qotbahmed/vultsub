<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "man_expenses_category".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $title_en
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $academy_id
 *
 * @property Academies $academy
 * @property ExpensesCategory[] $expensesCategories
 */
class ManExpensesCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'man_expenses_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'academy_id'], 'integer'],
            [['academy_id','title'], 'required'],
            [['title', 'title_en'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'title_en' => Yii::t('common', 'English Title'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
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
     * Get the translated title based on current language after finding the record
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Use the English title if the current language is English and the title_en field is not empty
        $currentLang = Yii::$app->language;
        if ($currentLang === 'en' && !empty($this->title_en)) {
            $this->title = $this->title_en;
        }
        // For Arabic (or any other language), we keep using the default 'title' field
    }

    /**
     * Gets query for [[ExpensesCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getExpensesCategories()
    // {
    //     return $this->hasMany(ExpensesCategory::class, ['main_category_id' => 'id']);
    // }
    public function getExpensesCategories()
{
    return $this->hasMany(ExpensesCategory::class, ['main_category_id' => 'id'])
        ->andWhere(['status' => ExpensesCategory::STATUS_ACTIVE]);
}


      /**
     * Loads the model with given data.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model and related models if any.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     *
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
