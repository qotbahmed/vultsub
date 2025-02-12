<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\SponsorLogQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "sponsor_log".
 *
 * @property integer $id
 * @property integer $sponsor_id
 * @property string $amount
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\Sponsors $sponsor
 */
class SponsorLog extends ActiveRecord
{

    use RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            'sponsor'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsor_id', 'amount'], 'required'],
            [['sponsor_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sponsor_log';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sponsor_id' => 'Sponsor ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSponsor()
    {
        return $this->hasOne(\common\models\Sponsors::className(), ['id' => 'sponsor_id']);
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
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],

        ];
    }

    /**
     * @inheritdoc
     * @return SponsorLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SponsorLogQuery(get_called_class());
    }
}
