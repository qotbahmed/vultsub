<?php

namespace common\models;

use common\models\Sport;    
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "team_position".
 *
 * @property int $id
 * @property int $sport_id Related Sport ID
 * @property string $title Position Title
 * @property string|null $description Description of the position
 * @property int $status Status: 1=Active, 0=Inactive
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Sport $sport
 */
class TeamPosition extends \yii\db\ActiveRecord
{
const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team_position';
    }
     public function behaviors()
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['sport_id', 'title'], 'required'],
            [['sport_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sport_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'sport_id' => Yii::t('common', 'Sport ID'),
            'title' => Yii::t('common', 'Title'),
            'description' => Yii::t('common', 'Description'),
            'status' => Yii::t('common', 'Status'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
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
 public function getStatusText()
    {
        return $this->status == self::STATUS_ACTIVE ? Yii::t('backend', 'Active') : Yii::t('backend', 'Inactive');
    }
public function getAcademySport()
{
    return $this->hasOne(AcademySport::class, ['sport_id' => 'sport_id']);
}
}
