<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "facility_type".
 *
 * @property int $id
 * @property string $title Type of facility, e.g., Tennis Court, Swimming Pool
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $academy_id
 * @property string|null $title_en

 *
 * @property Facilities[] $facilities
 * @property Academies $academy
 */
class FacilityType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facility_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'academy_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['title_en'], 'string', 'max' => 255],
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
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'title_en' => Yii::t('common', 'English Title'),

        ];
    }

    /**
     * Gets query for [[Facilities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacilities()
    {
        return $this->hasMany(Facilities::class, ['facility_type_id' => 'id']);
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
     * Save all models.
     *
     * @param array $models
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Load all models.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Magic method to handle property access
     * This ensures that when 'title' is accessed, it automatically returns the localized version
     * 
     * @param string $name Name of the property being accessed
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'title' && Yii::$app->language === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        return parent::__get($name);
    }

    /**
     * Returns the localized title based on the current application language
     * 
     * @return string The sport title in the current language
     */
    public function getLocalizedTitle()
    {
        return Yii::$app->language === 'en' && !empty($this->title_en)
            ? $this->title_en
            : $this->title;
    }
}
