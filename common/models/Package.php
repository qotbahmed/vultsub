<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\Academies;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "package".
 *
 * @property int $id
 * @property string $name
 * @property int $sport_id
 * @property int|null $classes
 * @property int $amount
 * @property string $created_at
 * @property string $updated_at
 * @property int $academy_id
 * @property int|null $updated_by
 * @property int|null $created_by
 * @property int|null $status
 * @property int $package_type
 * @property int|null $duration_type
 * @property int|null $custom_duration_days
 * @property Academies $academy
 * @property Sport $sport
 * 
 */
class Package extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    // Package Types
    const PACKAGE_TYPE_CLASSES_ONLY = 1;
    const PACKAGE_TYPE_DURATION_ONLY = 2;
    const PACKAGE_TYPE_CLASSES_AND_DURATION = 3;

    // Duration Types
    const DURATION_TYPE_MONTH = 1;
    const DURATION_TYPE_3_MONTHS = 2;
    const DURATION_TYPE_6_MONTHS = 3;
    const DURATION_TYPE_YEAR = 4;
    const DURATION_TYPE_CUSTOM = 5;

    public $packages = [];
    public $academy_ids = [];
    public $close = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['name', 'sport_id', 'amount', 'academy_id', 'package_type'], 'required'],
            [['sport_id', 'classes', 'amount', 'academy_id', 'updated_by', 'created_by', 'package_type', 'duration_type', 'custom_duration_days'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['sport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sport::class, 'targetAttribute' => ['sport_id' => 'id']],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['packages', 'close'], 'safe'],
            [['academy_ids'], 'each', 'rule' => ['integer']],
            [['academy_ids'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => 'id']],
            ['amount', 'number', 'min' => 1, 'max' => 100000, 'message' => Yii::t('common', 'Price must be a positive number.')],
            [['name'], 'unique', 'targetAttribute' => ['name', 'academy_id', 'sport_id'], 'message' => Yii::t('common', 'This name has already been taken for this sport in the selected academy.')],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['package_type', 'default', 'value' => self::PACKAGE_TYPE_CLASSES_ONLY],
            ['package_type', 'in', 'range' => [self::PACKAGE_TYPE_CLASSES_ONLY, self::PACKAGE_TYPE_DURATION_ONLY, self::PACKAGE_TYPE_CLASSES_AND_DURATION]],
            ['duration_type', 'in', 'range' => [self::DURATION_TYPE_MONTH, self::DURATION_TYPE_3_MONTHS, self::DURATION_TYPE_6_MONTHS, self::DURATION_TYPE_YEAR, self::DURATION_TYPE_CUSTOM]],
            
            // Conditional validation rules
            ['classes', 'required', 'when' => function($model) {
                return in_array($model->package_type, [self::PACKAGE_TYPE_CLASSES_ONLY, self::PACKAGE_TYPE_CLASSES_AND_DURATION]);
            }],
            ['duration_type', 'required', 'when' => function($model) {
                return in_array($model->package_type, [self::PACKAGE_TYPE_DURATION_ONLY, self::PACKAGE_TYPE_CLASSES_AND_DURATION]);
            }],
            ['custom_duration_days', 'required', 'when' => function($model) {
                return $model->duration_type == self::DURATION_TYPE_CUSTOM;
            }],
            ['custom_duration_days', 'integer', 'min' => 1, 'max' => 3650], // Max 10 years
        ];
    }


    public function validateMobile($attribute, $params)
    {
        // Convert to English numbers first
        $this->$attribute = $this->convertToEnglishNumbers($this->$attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name Of Package'),
            'sport_id' => Yii::t('common', 'Sport'),
            'classes' => Yii::t('common', 'Classes'),
            'amount' => Yii::t('common', 'cost'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'academy_ids' => Yii::t('common', 'Branch'),
            'academy_id' => Yii::t('common', 'Branch'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_by' => Yii::t('common', 'Created By'),
            'package_type' => Yii::t('common', 'Package Type'),
            'duration_type' => Yii::t('common', 'Duration Type'),
            'custom_duration_days' => Yii::t('common', 'Custom Duration (Days)'),
        ];
    }

    public  function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }

    public function packageStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">' . Yii::t('backend', 'Not Active') . '</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">' . Yii::t('backend', 'Active') . '</span>'
        ];
    }

    public static function getStatuses($controllerType)
    {
        $statuses = [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];


        return $statuses;
    }

    /**
     * Get package types array
     * @return array
     */
    public static function getPackageTypes()
    {
        return [
            self::PACKAGE_TYPE_CLASSES_ONLY => Yii::t('common', 'Classes Only'),
            self::PACKAGE_TYPE_DURATION_ONLY => Yii::t('common', 'Duration Only'),
            self::PACKAGE_TYPE_CLASSES_AND_DURATION => Yii::t('common', 'Classes and Duration'),
        ];
    }

    /**
     * Get duration types array
     * @return array
     */
    public static function getDurationTypes()
    {
        return [
            self::DURATION_TYPE_MONTH => Yii::t('common', '1 Month'),
            self::DURATION_TYPE_3_MONTHS => Yii::t('common', '3 Months'),
            self::DURATION_TYPE_6_MONTHS => Yii::t('common', '6 Months'),
            self::DURATION_TYPE_YEAR => Yii::t('common', '1 Year'),
            self::DURATION_TYPE_CUSTOM => Yii::t('common', 'Custom'),
        ];
    }

    /**
     * Get package type name
     * @return string
     */
    // public function getPackageTypeName()
    // {
    //     $types = self::getPackageTypes();
    //     return isset($types[$this->package_type]) ? $types[$this->package_type] : '';
    // }

    /**
     * Get duration type name
     * @return string
     */
    public function getDurationTypeName()
    {
        $types = self::getDurationTypes();
        return isset($types[$this->duration_type]) ? $types[$this->duration_type] : '';
    }

    /**
     * Get duration in days based on duration type
     * @return int|null
     */
    public function getDurationInDays()
    {
        switch ($this->duration_type) {
            case self::DURATION_TYPE_MONTH:
                return 30;
            case self::DURATION_TYPE_3_MONTHS:
                return 90;
            case self::DURATION_TYPE_6_MONTHS:
                return 180;
            case self::DURATION_TYPE_YEAR:
                return 365;
            case self::DURATION_TYPE_CUSTOM:
                return $this->custom_duration_days;
            default:
                return null;
        }
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
     * Gets query for [[Sport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSport()
    {
        return $this->hasOne(Sport::class, ['id' => 'sport_id']);
    }


    public function loadAll($data)
    {
        $loaded = $this->load($data);

        if (isset($data['Package']['packages']) && is_array($data['Package']['packages'])) {
            $this->packages = $data['Package']['packages'];
        }

        return $loaded;
    }


    public function saveAll($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->save($runValidation, $attributeNames)) {
                Yii::error('Failed to save main model: ' . print_r($this->errors, true));
                $transaction->rollBack();
                return false;
            }

            // Assuming 'academy_ids' is an array of IDs of the academies related to this package
            if (is_array($this->academy_ids)) {
                foreach ($this->academy_ids as $academyId) {

                    $this->academy_id = $academyId;

                    if (!$this->save($runValidation)) {
                        Yii::error('Failed to save package with academy_id: ' . $academyId . ' - ' . print_r($this->errors, true));
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            Yii::error("Error saving package: " . $e->getMessage());
            $transaction->rollBack();
            throw $e;
        }
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
    
    public function getAcademySport()
    {
        return $this->hasOne(AcademySport::class, ['id' => 'academy_sport_id']);
    }

    public function getSports()
    {
        return $this->hasOne(Sport::class, ['id' => 'sport_id'])
            ->via('academySport');
    }

    /**
     * Get package type name in Arabic
     * @return string
     */
    public function getPackageTypeName()
    {
        switch ($this->package_type) {
            case self::PACKAGE_TYPE_CLASSES_ONLY:
                return 'محددة بعدد حصص';
            case self::PACKAGE_TYPE_DURATION_ONLY:
                return 'محددة بفترة زمنية';
            case self::PACKAGE_TYPE_CLASSES_AND_DURATION:
                return 'محددة بعدد حصص وفترة';
            default:
                return 'غير محدد';
        }
    }
}
