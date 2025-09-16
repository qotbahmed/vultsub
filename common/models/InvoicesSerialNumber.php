<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoices_serial_number".
 *
 * @property int $id
 * @property int|null $academy_id
 * @property int $serial_number
 *
 * @property Academies $academy
 */
class InvoicesSerialNumber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices_serial_number';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['academy_id', 'serial_number'], 'integer'],
            [['serial_number'], 'required'],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
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
            'serial_number' => Yii::t('common', 'Serial Number'),
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
}
