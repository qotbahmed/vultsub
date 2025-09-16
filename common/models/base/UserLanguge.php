<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\UserLangugeQuery;
use mootensai\relation\RelationTrait;

/**
 * This is the base model class for table "user_languge".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $lang_id
 *
 * @property \common\models\User $user
 * @property \common\models\Language $lang
 */
class UserLanguge extends ActiveRecord
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
            'lang'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'lang_id'], 'required'],
            [['user_id', 'lang_id'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_languge';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'user_id' => Yii::t('backend', 'User ID'),
            'lang_id' => Yii::t('backend', 'Lang ID'),
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
    public function getLang()
    {
        return $this->hasOne(\common\models\Language::className(), ['id' => 'lang_id']);
    }

    /**
     * @inheritdoc
     * @return UserLangugeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserLangugeQuery(get_called_class());
    }
}
