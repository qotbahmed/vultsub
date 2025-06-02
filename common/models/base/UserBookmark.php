<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\UserBookmarkQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "user_bookmark".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ayah_num
 * @property string $page_num
 * @property string $surah
 * @property string $note
 *
 * @property \common\models\User $user
 */
class UserBookmark extends ActiveRecord
{

    use RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            'user'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['ayah_num', 'page_num', 'note','surah'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_bookmark';
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ayah_num' => 'Ayah Num',
            'page_num' => 'Page Num',
            'surah' => 'Surah',
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
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     * @return UserBookmarkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserBookmarkQuery(get_called_class());
    }
}
