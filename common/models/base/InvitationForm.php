<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\query\InvitationFormQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "invitation_form".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $q_prefer_to
 * @property string $q_right_time
 * @property string $q_sport
 * @property string $q_age
 * @property string $created_at
 * @property string $updated_at
 */
class InvitationForm extends ActiveRecord
{

    use RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            ''
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q_prefer_to'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['phone', 'q_right_time', 'q_sport', 'q_age'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 200]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invitation_form';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'q_prefer_to' => 'Q Prefer To',
            'q_right_time' => 'Q Right Time',
            'q_sport' => 'Q Sport',
            'q_age' => 'Q Age',
        ];
    }

/**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],

        ];
    }

    /**
     * @inheritdoc
     * @return InvitationFormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvitationFormQuery(get_called_class());
    }
}
