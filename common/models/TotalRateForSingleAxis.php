<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tatal_rate_for_single_axis".
 *
 * @property int $id
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int $academy_id
 * @property float|null $value
 * @property int $axis_id
 *
 * @property Academies $academy
 * @property Axis $axis
 */
class TotalRateForSingleAxis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'total_rate_for_single_axis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_id', 'axis_id'], 'required'],
            [['academy_id', 'axis_id'], 'integer'],
            [['value'], 'number'],
            [['updated_at', 'created_at'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['axis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Axis::class, 'targetAttribute' => ['axis_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_at' => Yii::t('common', 'Created At'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'value' => Yii::t('common', 'Value'),
            'axis_id' => Yii::t('common', 'Axis ID'),
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
     * Gets query for [[Axis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAxis()
    {
        return $this->hasOne(Axis::class, ['id' => 'axis_id']);
    }
}