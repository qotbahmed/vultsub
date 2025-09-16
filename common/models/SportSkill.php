<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sport_skill".
 *
 * @property int $id
 * @property string|null $title
 * @property int $sport_id
 * @property int|null $status
 * @property int|null $added_by
 * @property int|null $title_en
 *
 * @property Sport $sport
 */
class SportSkill extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sport_skill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sport_id'], 'required'],
            [['sport_id', 'status', 'added_by'], 'integer'],
            [['title', 'title_en'], 'string', 'max' => 255],
            [
                ['sport_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sport::class,
                'targetAttribute' => ['sport_id' => 'id']
            ],
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
            'sport_id' => Yii::t('common', 'Sport'),
            'status' => Yii::t('common', 'Status'),
            'added_by' => Yii::t('common', 'Added By'),
            'title_en' => Yii::t('common', 'English Title'),

        ];
    }

    /**
     * Gets query for [[Sport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sport::class, ['id' => 'sport_id']);
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
}
