<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "players_skills_levels".
 *
 * @property int $id
 * @property int|null $level_id
 * @property int|null $academy_skills_id
 * @property int|null $academy_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Academies $academy
 * @property AcademySportSkill $academySkills
 * @property PlayersLevels $level
 */
class PlayersSkillsLevels extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'players_skills_levels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level_id', 'academy_skills_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayersLevels::class, 'targetAttribute' => ['level_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['academy_skills_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySportSkill::class, 'targetAttribute' => ['academy_skills_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'level_id' => Yii::t('common', 'Level ID'),
            'academy_skills_id' => Yii::t('common', 'Academy Skills ID'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
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
     * Gets query for [[AcademySkills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySkills()
    {
        return $this->hasOne(AcademySportSkill::class, ['id' => 'academy_skills_id']);
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(PlayersLevels::class, ['id' => 'level_id']);
    }
}