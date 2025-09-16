<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "external_playground_players".
 *
 * @property int $id
 * @property int|null $external_playground_id
 * @property int|null $player_id
 * @property int|null $acceptance_status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property ExternalPlayground $externalPlayground
 * @property User $player
 */
class ExternalPlaygroundPlayers extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'external_playground_players';
    }

    const ACCEPTANCE_APPROVED = 1;
    const ACCEPTANCE_PENDING  = 2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['external_playground_id', 'player_id', 'acceptance_status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['external_playground_id', 'player_id', 'acceptance_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['external_playground_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExternalPlayground::class, 'targetAttribute' => ['external_playground_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'external_playground_id' => Yii::t('common', 'External Playground ID'),
            'player_id' => Yii::t('common', 'Player ID'),
            'acceptance_status' => Yii::t('common', 'Acceptance Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
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
     * Gets query for [[ExternalPlayground]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExternalPlayground()
    {
        return $this->hasOne(ExternalPlayground::class, ['id' => 'external_playground_id']);
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'player_id']);
    }

    public static function getAcceptanceStatusList()
    {
        return [
            self::ACCEPTANCE_PENDING  => Yii::t('common', 'Pending'),
            self::ACCEPTANCE_APPROVED => Yii::t('common', 'Approved'),
        ];
    }

    public function getAcceptanceStatusName()
    {
        $list = self::getAcceptanceStatusList();
        return [
            'id'   => $this->acceptance_status,
            'name' => $list[$this->acceptance_status] ?? null
        ];
    }
}
