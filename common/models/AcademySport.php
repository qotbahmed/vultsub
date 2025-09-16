<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "academy_sport".
 *
 * @property int $id
 * @property int $sport_id
 * @property int $academy_id
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $color
 *
 * @property Academies $academy
 * @property AcademySportSkill[] $academySportSkills
 * @property Sport $sport
 */
class AcademySport extends \yii\db\ActiveRecord
{
    public $sport_skill_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academy_sport';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sport_id', 'academy_id'], 'required'],
            [['sport_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['color'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sport_id' => 'id']],
            [['sport_id', 'academy_id'], 'unique', 'targetAttribute' => ['sport_id', 'academy_id'], 'message' => Yii::t('common', 'This sport is already added to the academy.')],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'sport_id' => Yii::t('common', 'Sport'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'color' => Yii::t('common', 'Color'),
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
     * Gets query for [[AcademySportSkills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySportSkills()
    {
        return $this->hasMany(AcademySportSkill::class, ['academy_sport_id' => 'id']);
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


    public function getTitle()
    {
        return $this->sport ? $this->sport->title : null;
    }
    // in common\models\AcademySport
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['academy_sport_id' => 'id']);
    }
    
    public function getPositions()
    {
        return $this->hasMany(TeamPosition::class, ['sport_id' => 'sport_id']);
    }
    
    /**
     * Gets localized sport title based on current language
     *
     * @return string|null Localized sport title
     */
    public function getLocalizedTitle()
    {
        if (!$this->sport) {
            return null;
        }
        
        return Yii::$app->language === 'en' && !empty($this->sport->title_en) 
            ? $this->sport->title_en 
            : $this->sport->title;
    }
}
