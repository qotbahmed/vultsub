<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "academy_sport_skill".
 *
 * @property int $id
 * @property int $academy_sport_id
 * @property string|null $title
 * @property int|null $added_by
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int $category_id
 * @property int $sport_skill_id


 *
 * @property AcademySport $academySport
 */
class AcademySportSkill extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academy_sport_skill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_sport_id'], 'required'],
            [['academy_sport_id', 'added_by', 'category_id', 'sport_skill_id'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['academy_sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySport::class, 'targetAttribute' => ['academy_sport_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
            'title' => Yii::t('common', 'Title'),
            'added_by' => Yii::t('common', 'Added By'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_at' => Yii::t('common', 'Created At'),
            'category_id' => Yii::t('common', 'Category Id'),
            'sport_skill_id' => Yii::t('common', 'Sport Skill Id'),
        ];
    }

    /**
     * Gets query for [[AcademySport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySport()
    {
        return $this->hasOne(AcademySport::class, ['id' => 'academy_sport_id']);
    }

    public function getSportSkill()
    {
        return $this->hasOne(SportSkill::class, ['id' => 'sport_skill_id']);
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
    public static function getSkillsList($academy_sport_id)
    {
        if (!$academy_sport_id) {
            return [];
        }

        $skills = self::find()
            ->where(['academy_sport_id' => $academy_sport_id])
            ->all();

        return ArrayHelper::map($skills, 'id', 'title');
    }

    public static function getUnassignedSkillsList($academy_sport_id)
    {
        if (!$academy_sport_id) {
            return [];
        }

        $skills = self::find()
            ->where(['academy_sport_id' => $academy_sport_id])
            ->andWhere(['category_id' => null])
            ->all();

        return \yii\helpers\ArrayHelper::map($skills, 'id', function ($model) {
            return Yii::$app->language === 'ar' ? $model->title : $model->title;
        });
    }

    public static function getAvailableSkillsIncludingCategory($academy_sport_id, $categoryId = null)
    {
        if (!$academy_sport_id) {
            return [];
        }

        $query = self::find()
            ->where(['academy_sport_id' => $academy_sport_id])
            ->andWhere([
                'or',
                ['category_id' => null],
                ['category_id' => $categoryId]
            ]);

        $skills = $query->all();

        return ArrayHelper::map($skills, 'id', function ($model) {
            return Yii::$app->language === 'ar' ? $model->title : $model->title;
        });
    }
}
