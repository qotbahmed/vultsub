<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\PointsLogsQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "points_logs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_mobile
 * @property integer $points_num
 * @property integer $type
 * @property integer $page_num
 * @property integer $time
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property \common\models\User $user
 */
class PointsLogs extends ActiveRecord
{
     const TYPE_ADD = 0;
     const TYPE_WITHDRAW = 1;

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
            [['user_id', 'points_num',
                'page_num', 'time', 'created_by', 'updated_by'], 'integer'],
            [['type'], 'integer'],
            [['user_name', 'user_mobile',], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'points_logs';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_mobile' => 'User Mobile',
            'points_num' => 'Points Num',
            'type' => 'Type',
            'page_num' => 'Page Num',
            'time' => 'Time',
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
     * @return PointsLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PointsLogsQuery(get_called_class());
    }
}
