<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "external_playground".
 *
 * @property int $id
 * @property int|null $academy_id
 * @property int|null $academy_sport_id
 * @property string|null $date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $location
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $status
 *
 * @property Academies $academy
 * @property AcademySport $academySport
 * @property ExternalPlaygroundPlayers[] $externalPlaygroundPlayers
 */
class ExternalPlayground extends \yii\db\ActiveRecord
{

    public $player_ids = [];
    const STATUS_SEND_NOW = 1;
    const STATUS_SEND_LATER = 2;



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'external_playground';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_sport_id', 'date', 'start_time', 'end_time', 'location', 'description', 'status'], 'required'],
            [['academy_id', 'academy_sport_id', 'date', 'start_time', 'end_time', 'location', 'description', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['academy_id', 'academy_sport_id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['date', 'start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['location'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['academy_sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademySport::class, 'targetAttribute' => ['academy_sport_id' => 'id']],
            ['player_ids', 'required', 'message' => Yii::t('common', 'At least one player must be selected')],
            ['player_ids', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'academy_sport_id' => Yii::t('common', 'Academy Sport ID'),
            'date' => Yii::t('common', 'Date'),
            'start_time' => Yii::t('common', 'Start Time'),
            'end_time' => Yii::t('common', 'End Time'),
            'location' => Yii::t('common', 'Location'),
            'description' => Yii::t('common', 'Description'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'status' => Yii::t('common', 'Send Status'),
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
     * Gets query for [[ExternalPlaygroundPlayers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExternalPlaygroundPlayers()
    {
        return $this->hasMany(ExternalPlaygroundPlayers::class, ['external_playground_id' => 'id']);
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_SEND_NOW   => Yii::t('common', 'Send Now'),
            self::STATUS_SEND_LATER => Yii::t('common', 'Send Later'),
        ];
    }

    public function loadAll($data)
    {
        return $this->load($data);
    }

    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     * @return bool Whether the deletion was successful.
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add additional deletion logic here, if necessary.

            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
