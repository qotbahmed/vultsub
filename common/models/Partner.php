<?php

namespace common\models;

use trntv\filekit\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

use Yii;

/**
 * This is the model class for table "partner".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $username
 * @property string $email
 * @property string $mobile
 * @property int $status
 * @property float|null $total_contribution
 * @property string|null $avatar_path
 * @property string|null $avatar_base_url
 * @property int|null $gender
 * @property string|null $dob
 * @property string|null $nationality
 * @property int|null $academy_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $created_by
 * @property string|null $notes
 * @property int|null $type_of_safe

 *
 * @property Academies $academy
 */
class Partner extends \yii\db\ActiveRecord
{
    public $image;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner';
    }
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
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'username', 'avatar_path', 'avatar_base_url', 'gender', 'dob', 'nationality', 'academy_id', 'updated_by', 'created_by', 'notes'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 2],
            [['total_contribution'], 'default', 'value' => 0.00],
            [['email', 'mobile'], 'required'],
            [['created_at', 'updated_at', 'image', 'dob'], 'safe'],

            [['status', 'gender', 'academy_id', 'updated_by', 'created_by'], 'integer'],
            [['total_contribution'], 'number'],
            [['notes'], 'string'],
            [['name', 'username', 'email', 'mobile', 'avatar_path', 'avatar_base_url', 'nationality'], 'string', 'max' => 255],
            [['academy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academies::class, 'targetAttribute' => ['academy_id' => 'id']],
         //   ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            [['type_of_safe'], 'default', 'value' => Transactions::SAFE_TYPE_CASH],
            [['type_of_safe'], 'integer'],
           // [['type_of_safe'], 'required'],
            [['type_of_safe'], 'in', 'range' => [Transactions::SAFE_TYPE_CASH, Transactions::SAFE_TYPE_BANK]],

            

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'username' => Yii::t('common', 'Username'),
            'email' => Yii::t('common', 'Email'),
            'mobile' => Yii::t('common', 'Mobile'),
            'status' => Yii::t('common', 'Status'),
            'total_contribution' => Yii::t('common', 'Total Contribution'),
            'avatar_path' => Yii::t('common', 'Avatar Path'),
            'avatar_base_url' => Yii::t('common', 'Avatar Base Url'),
            'gender' => Yii::t('common', 'Gender'),
            'dob' => Yii::t('common', 'Dob'),
            'nationality' => Yii::t('common', 'Nationality'),
            'academy_id' => Yii::t('common', 'Academy ID'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_by' => Yii::t('common', 'Created By'),
            'notes' => Yii::t('common', 'Notes'),
            'type_of_safe' => Yii::t('common', 'Type of Safe'),

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
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model with optional validation and attribute selection.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }

    /**
     * Deletes the model and related models if any.
     * 
     * @return bool Whether the deletion was successful.
     * @throws \Exception
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
    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . '/' . $this->avatar_path)
            : $default;
    }
    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted'),
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->dob) {
                $this->dob = date('Y-m-d', strtotime($this->dob));
            }
            return true;
        }
        return false;
    }
    public function getUserGender()
    {
        if ($this->gender == self::GENDER_MALE) {
            return Yii::t('common', 'Male');
        } elseif ($this->gender == self::GENDER_FEMALE) {
            return Yii::t('common', 'Female');
        } else {
            return Yii::t('backend', 'Unknown');
        }
    }
    public static function getStatuses($controllerType)
    {
        $statuses = [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];


        return $statuses;
    }
    public function partnersStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">' . Yii::t('backend', 'Not Active') . '</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">' . Yii::t('backend', 'Active') . '</span>'
        ];
    }

    public function recordContribution($amount, $paymentMethod, $receiptNumber = null, $note = null, $typeOfSafe = null)

    {
        if (!$this->academy_id) {
            Yii::error("Academy ID not found for Partner ID: {$this->id}");
            return false;
        }
    
        $transaction = Yii::$app->db->beginTransaction();
    
        try {
            $this->total_contribution += abs($amount);
            if (!$this->save(false, ['total_contribution', 'updated_at', 'updated_by'])) {
                throw new \Exception("Unable to update total contribution for Partner ID: {$this->id}");
            }
    
            $financialBalance = FinancialBalance::findOrCreate(['academy_id' => $this->academy_id]);
            $typeOfSafe = $this->type_of_safe;

           $this->createTransaction($financialBalance, $typeOfSafe, $amount, $paymentMethod, $receiptNumber, $note);
    
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            return false;
        }
    }
    
    
    
    protected function createTransaction($financialBalance, $typeOfSafe, $amount, $paymentMethod, $receiptNumber, $note)
    {
        $prefixes = Transactions::getReceiptPrefixes();
        $prefix = $prefixes[Transactions::SOURCE_PARTNER] ?? 'PAR';
        $formattedReceipt = $prefix . ($receiptNumber ?? uniqid());
    
        $transaction = new Transactions([
            'academy_id' => $financialBalance->academy_id,
            'type' => Transactions::TYPE_FINANCIAL,
            'source' => Transactions::SOURCE_PARTNER,
            'source_id' => $this->id,
            'amount' => abs($amount),
            'payment_method' => $paymentMethod,
            'receipt_number' => $formattedReceipt,
            'financial_balance_id' => $financialBalance->id,
            'type_of_safe' => $typeOfSafe,
            'note' => $note ?? 'Partner Contribution',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_by' => Yii::$app->user->id,
            'updated_by' => Yii::$app->user->id,
        ]);
    
        if (!$transaction->save(false)) {
            throw new \Exception("Failed to log transaction for Partner ID: {$this->id}");
        }
    
        $financialBalance->updateBalance(abs($amount), $typeOfSafe, null);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
 
    }
    

    protected function handleContributionChange($difference)
    {
        if (!$this->academy_id) {
            Yii::error("Missing academy_id for Partner ID: {$this->id}");
            return;
        }
    
        $financialBalance = FinancialBalance::findOrCreate(['academy_id' => $this->academy_id]);
        $typeOfSafe = $this->type_of_safe ?? Transactions::SAFE_TYPE_CASH;
    
        $financialBalance->updateBalance($difference, $typeOfSafe, null);
    
        $transaction = new Transactions([
            'academy_id' => $this->academy_id,
            'financial_balance_id' => $financialBalance->id,
            'type' => Transactions::TYPE_FINANCIAL,
            'source' => Transactions::SOURCE_PARTNER,
            'source_id' => $this->id,
            'amount' => abs($difference),
            'payment_method' => Transactions::PAYMENT_METHOD_CASH, 
            'type_of_safe' => $typeOfSafe,
            'note' => $difference > 0 ? 'زيادة مساهمة شريك' : 'نقص مساهمة شريك',
            'receipt_number' => 'PAR' . uniqid(),
            'created_by' => Yii::$app->user->id ?? null,
            'updated_by' => Yii::$app->user->id ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    
        if (!$transaction->save(false)) {
            Yii::error("Failed to save partner contribution transaction for Partner ID: {$this->id}");
        }
    }
    
        
        
}
