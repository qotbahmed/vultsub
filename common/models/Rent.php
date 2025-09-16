<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use DateTime;

/**
 * This is the model class for table "rent".
 *
 * @property int $id
 * @property string $statement
 * @property string|null $note
 * @property int|null $payment_status
 * @property string|null $owner
 * @property int|null $type
 * @property string|null $period
 * @property string|null $start_date
 * @property string|null $end_date
 * @property float|null $sub_total
 * @property float|null $tax
 * @property float|null $total
 * @property float|null $amount_paid
 * @property float|null $remaining_amount
 * @property int|null $payment_method
 * @property int|null $academy_id
 * @property int|null $facility_id
 * @property int|null $facility_type_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Academies $academy
 * @property Facilities $facility
 * @property FacilityType $facilityType
 * @property int|null $receipt_number
 */
class Rent extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    const TYPE_ANNUAL = 1;
    const TYPE_MONTHLY = 2;
    const TYPE_WEEKLY = 3;
    const TYPE_DAILY = 4;
    const TYPE_HOURLY = 5;

    const PAYMENT_SUBPAYMENT = 1;
    const PAYMENT_COMPLETED = 0;

    const PAYMENT_METHODS = [
        1 => 'شبكة',
        2 => 'نقدي',
        3 => 'تحويل بنكي',
        4 => 'STC Pay',
        5 => 'اخري',
    ];

    const PAYMENT_METHOD_NETWORK   = 1;
    const PAYMENT_METHOD_CASH      = 2;
    const PAYMENT_METHOD_TRANSFER  = 3;
    const PAYMENT_METHOD_STC_PAY   = 4;
    const METHOD_OTHER = 5;


    public static function getPaymentMethodOptions()
    {
        return self::PAYMENT_METHODS;
    }
    public function getLatestPaymentMethodText()
    {
        $latestPayment = $this->getRentPayments()->orderBy(['created_at' => SORT_DESC])->one();
        return $latestPayment ? $latestPayment->getPaymentMethodText() : Yii::t('common', 'No Payment');
    }
    public static function tableName()
    {
        return 'rent';
    }

    public function rules()
    {
        return [
            [['statement', 'owner', 'facility_id', 'facility_type_id', 'type', 'period', 'start_date', 'sub_total', 'payment_method', 'amount_paid', 'payment_status', 'receipt_number'], 'required'],
            [['note'], 'string'],
            [['payment_status', 'type', 'academy_id', 'facility_id', 'facility_type_id', 'created_by', 'updated_by', 'payment_method', 'receipt_number'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['sub_total', 'tax', 'total', 'amount_paid', 'remaining_amount'], 'number'],
            [['statement'], 'string', 'max' => 100],
            [['owner', 'period'], 'string', 'max' => 255],
            [['period'], 'validatePeriod'],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
            [['facility_id'], 'exist', 'skipOnError' => true, 'targetClass' => Facilities::class, 'targetAttribute' => ['facility_id' => 'id']],
            [['facility_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FacilityType::class, 'targetAttribute' => ['facility_type_id' => 'id']],
            [['statement', 'owner'], 'match', 'pattern' => '/^[\p{L}][\p{L}0-9\s]{2,}$/u', 'message' => Yii::t('common', 'Field must start with a letter, contain at least 3 characters, and contain only letters, numbers, and spaces.')],
            ['period', 'number', 'min' => 1, 'message' => Yii::t('common', 'Period must be a positive number.')],
            ['sub_total', 'number', 'min' => 0.01, 'message' => Yii::t('common', 'Sub Total must be a positive number.')],
            ['amount_paid', 'number', 'min' => 0.01, 'message' => Yii::t('common', 'Amount Paid must be greater than zero.')],
            [['receipt_number'], 'unique'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'statement' => Yii::t('backend', 'Statement'),
            'note' => Yii::t('backend', 'Note'),
            'payment_status' => Yii::t('backend', 'Payment Status'),
            'owner' => Yii::t('backend', 'Owner'),
            'type' => Yii::t('backend', 'Type'),
            'period' => Yii::t('backend', 'Period'),
            'start_date' => Yii::t('backend', 'Start Date'),
            'end_date' => Yii::t('common', 'End Date'),
            'sub_total' => Yii::t('backend', 'Sub Total'),
            'tax' => Yii::t('common', 'Tax'),
            'total' => Yii::t('common', 'Total'),
            'amount_paid' => Yii::t('common', 'Amount Paid'),
            'remaining_amount' => Yii::t('common', 'Remaining Amount'),
            'payment_method' => Yii::t('common', 'Payment Method'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'facility_id' => Yii::t('backend', 'Facility ID'),
            'facility_type_id' => Yii::t('common', 'Facility Type'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'receipt_number' => Yii::t('common', 'Receipt Number'),
        ];
    }

    public static function getPaymentStatusOptions()
    {
        return [
            self::PAYMENT_SUBPAYMENT => Yii::t('common', 'Partial Payment'),
            self::PAYMENT_COMPLETED  => Yii::t('common', 'Full Payment'),
        ];
    }


    public static function getTypeOptions()
    {
        return [
            self::TYPE_ANNUAL  => Yii::t('common', 'Annual'),
            self::TYPE_MONTHLY => Yii::t('common', 'Monthly'),
            self::TYPE_WEEKLY  => Yii::t('common', 'Weekly'),
            self::TYPE_DAILY   => Yii::t('common', 'Daily'),
            self::TYPE_HOURLY  => Yii::t('common', 'Hourly'),
        ];
    }


    public function validatePeriod($attribute, $params)
    {
        if ($this->type == self::TYPE_HOURLY && (!is_numeric($this->period) || $this->period < 1)) {
            $this->addError($attribute, Yii::t('common', 'Period must be a valid number of hours when the type is "ساعة".'));
        }
    }

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->payment_method = 1;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Convert start_date to the correct format, checking for valid input
            if ($this->start_date) {
                $date = DateTime::createFromFormat('Y-m-d', $this->start_date);
                if ($date) {
                    $this->start_date = $date->format('Y-m-d');
                } else {
                    $this->addError('start_date', Yii::t('common', 'Invalid start date format.'));
                    return false;
                }
            }

            // Calculate and set the end date
            if ($this->start_date && $this->type && $this->period) {
                $calculatedEndDate = $this->calculateEndDate();
                if ($calculatedEndDate) {
                    $this->end_date = $calculatedEndDate;
                } else {
                    $this->addError('end_date', Yii::t('common', 'Unable to calculate end date.'));
                    return false;
                }
            }

            return true;
        }
        return false;
    }

    protected function calculateEndDate()
    {
        $startDate = new DateTime($this->start_date);
        $period = (int)$this->period;

        switch ($this->type) {
            case self::TYPE_ANNUAL:
                return $startDate->modify("+$period year")->format('Y-m-d');
            case self::TYPE_MONTHLY:
                return $startDate->modify("+$period month")->format('Y-m-d');
            case self::TYPE_WEEKLY:
                return $startDate->modify("+$period week")->format('Y-m-d');
            case self::TYPE_DAILY:
                return $startDate->modify("+$period day")->format('Y-m-d');
            case self::TYPE_HOURLY:
                return $startDate->modify("+$period hour")->format('Y-m-d H:i:s');
            default:
                return $this->start_date;
        }
    }

    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id']);
    }

    public function getFacilities()
    {
        return $this->hasOne(Facilities::class, ['id' => 'facility_id']);
    }

    public function getFacilityType()
    {
        return $this->hasOne(FacilityType::class, ['id' => 'facility_type_id']);
    }

    /**
     * Deletes the model and related models if any.
     *
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
    public function getPaymentStatusText()
    {
        $statuses = self::getPaymentStatusOptions();
        return $statuses[$this->payment_status] ?? '';
    }
    public function getRentPayments()
    {
        return $this->hasMany(RentPayments::class, ['rent_id' => 'id']);
    }
    public function isRenewable()
    {
        if (!$this->end_date) {
            return false;
        }

        $endDate = new DateTime($this->end_date);
        $currentDate = new DateTime();

        if ($currentDate > $endDate) {
            return true;
        }

        $interval = $currentDate->diff($endDate);
        $daysRemaining = $interval->days;

        switch ($this->type) {
            case self::TYPE_WEEKLY:
                return $daysRemaining <= 3;

            case self::TYPE_MONTHLY:
                return $daysRemaining <= 7;

            case self::TYPE_ANNUAL:
                return $daysRemaining <= 30;

            case self::TYPE_DAILY:
            case self::TYPE_HOURLY:
                return true;

            default:
                return false;
        }
    }

    public static function getPaymentsMethodOptions()
    {
        return [
            self::PAYMENT_METHOD_NETWORK  => Yii::t('common', 'Network'),
            self::PAYMENT_METHOD_CASH     => Yii::t('common', 'Cash'),
            self::PAYMENT_METHOD_TRANSFER => Yii::t('common', 'Bank Transfer'),
            self::PAYMENT_METHOD_STC_PAY  => Yii::t('common', 'STC Pay'),
            self::METHOD_OTHER => Yii::t('common', 'Other'),

        ];
    }
}
