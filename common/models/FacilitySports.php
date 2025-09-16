<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "facility_sports".
 *
 * @property int $id
 * @property int $facility_id Foreign key to facilities table
 * @property int $sport_id Foreign key to academy_sport table
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Facilities $facility
 */
class FacilitySports extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facility_sports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['facility_id', 'sport_id'], 'required'],
            [['facility_id', 'sport_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['facility_id'], 'exist', 'skipOnError' => true, 'targetClass' => Facilities::class, 'targetAttribute' => ['facility_id' => 'id']],
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
            'facility_id' => Yii::t('common', 'Facility ID'),
            'sport_id' => Yii::t('common', 'Sport ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Facility]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacility()
    {
        return $this->hasOne(Facilities::class, ['id' => 'facility_id']);
    }
    // FacilitySports.php model



public function getSport()
{
    return $this->hasOne(Sport::class, ['id' => 'sport_id']);
}

}
