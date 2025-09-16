<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "players_levels".
 *
 * @property int $id
 * @property string|null $title
 * @property int $academy_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $academy_sport_id
 * @property array $skills (virtual property for handling skills)
 *
 * @property Academies $academy
 * @property AcademySport $academySport
 * @property PlayersSkillsLevels[] $playersSkillsLevels
 */
class PlayersLevels extends \yii\db\ActiveRecord
{
    public $skills; // Virtual property for handling skills

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'players_levels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_id'], 'required'],
            [['academy_id', 'created_by', 'updated_by', 'academy_sport_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['academy_sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySport::class, 'targetAttribute' => ['academy_sport_id' => 'id']],
            [['skills'], 'safe'], // Add validation for the virtual property
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
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
            'skills' => Yii::t('common', 'Skills'), // Label for the virtual property
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
     * Gets query for [[AcademySport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademySport()
    {
        return $this->hasOne(AcademySport::class, ['id' => 'academy_sport_id']);
    }

    /**
     * Gets query for [[PlayersSkillsLevels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayersSkillsLevels()
    {
        return $this->hasMany(PlayersSkillsLevels::class, ['level_id' => 'id']);
    }

    /**
     * Loads the skills related to this level.
     */
    public function loadSkills()
    {
        $this->skills = ArrayHelper::getColumn($this->getPlayersSkillsLevels()->all(), 'academy_skills_id');
    }

    /**
     * Saves the selected skills for this level using a transaction.
     */
    public function saveSkills()
    {
        // Use transaction to ensure both the level and skills are saved successfully
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Delete old skills
            PlayersSkillsLevels::deleteAll(['level_id' => $this->id]);

            // Save new skills
            if (!empty($this->skills)) {
                foreach ($this->skills as $skillId) {
                    $skillModel = new PlayersSkillsLevels();
                    $skillModel->level_id = $this->id;
                    $skillModel->academy_skills_id = $skillId;
                    $skillModel->academy_id = $this->academy_id; // Ensure academy_id is set
                    if (!$skillModel->save()) {
                        throw new \Exception('Failed to save skill with ID ' . $skillId);
                    }
                }
            }
            // Commit transaction if everything is successful
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            $transaction->rollBack();
            Yii::error('Error while saving skills: ' . $e->getMessage());
            return false;
        }
    }
}