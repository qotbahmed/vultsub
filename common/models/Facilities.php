<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "facilities".
 *
 * @property int $id
 * @property int $facility_type_id Foreign key to facility_type table
 * @property string $title Custom name of the facility
 * @property string|null $notes Additional notes for the facility
 * @property int|null $academy_id Foreign key to academies table
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Academies $academy
 * @property FacilitySports[] $facilitySports
 * @property FacilityType $facilityType
 */
class Facilities extends \yii\db\ActiveRecord
{
        public $total_amount; 

    public $sport_ids = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facilities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['facility_type_id', 'title'], 'required'],
            [['facility_type_id', 'academy_id', 'created_by', 'updated_by'], 'integer'],
            [['notes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
           
            [['facility_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FacilityType::class, 'targetAttribute' => ['facility_type_id' => 'id']],
           
            ['sport_ids', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'facility_type_id' => Yii::t('common', 'Facility Type ID'),
            'title' => Yii::t('common', 'Title'),
            'notes' => Yii::t('common', 'Notes'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
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
     * Gets query for [[FacilitySports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacilitySports()
    {
        return $this->hasMany(FacilitySports::class, ['facility_id' => 'id']);
    }

    /**
     * Gets query for [[FacilityType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacilityType()
    {
        return $this->hasOne(FacilityType::class, ['id' => 'facility_type_id']);
    }
    public static function loadAll()
    {
        return self::find()->all();
    }


    /**
     * Gets the sports linked via the facility_sports table.
     */
    public function getSports()
    {
        return $this->hasMany(Sport::class, ['id' => 'sport_id'])
            ->via('facilitySports');
    }
  
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        // Begin the transaction
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Save the main facility model
            if (!$this->save($runValidation, $attributeNames)) {
                Yii::error('Failed to save facility: ' . print_r($this->errors, true));
                $transaction->rollBack();
                return false;
            }
    
            // Delete existing sports related to this facility
            FacilitySports::deleteAll(['facility_id' => $this->id]);
    
            // Check if sport_ids is an array and save each sport
            if (is_array($this->sport_ids)) {
                foreach ($this->sport_ids as $sportId) {
                    $facilitySport = new FacilitySports();
                    $facilitySport->facility_id = $this->id;
                    $facilitySport->sport_id = $sportId;
    
                    // Attempt to save the related sport record
                    if (!$facilitySport->save($runValidation)) {
                        Yii::error('Failed to save sport with sport_id: ' . $sportId . ' - ' . print_r($facilitySport->errors, true));
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
    
            // Commit the transaction
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            // Roll back transaction in case of any exception
            Yii::error("Error saving facility with sports: " . $e->getMessage());
            $transaction->rollBack();
            throw $e;
        }
    }
    public function getRents()
    {
        return $this->hasMany(Rent::class, ['facility_id' => 'id']);
    }
    }
