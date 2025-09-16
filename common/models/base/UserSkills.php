<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\UserSkillsQuery;
use mootensai\relation\RelationTrait;

/**
 * This is the base model class for table "user_skills".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $skill_id
 *
 * @property \common\models\User $user
 * @property \common\models\Skill $skill
 */
class UserSkills extends ActiveRecord
{

    use RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            'user',
            'skill'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'skill_id'], 'required'],
            [['user_id', 'skill_id'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_skills';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'user_id' => Yii::t('backend', 'User ID'),
            'skill_id' => Yii::t('backend', 'Skill ID'),
            'skill_id' => Yii::t('backend', 'Skill ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkill()
    {
        return $this->hasOne(\common\models\Skill::className(), ['id' => 'skill_id']);
    }

    /**
     * @inheritdoc
     * @return UserSkillsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSkillsQuery(get_called_class());
    }
}
