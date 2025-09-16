<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;

/**
 * This is the model class for table "club_form".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 * @property string $title
 * @property string $contact_phone
 * @property string|null $contact_email
 * @property string|null $name
 * @property int|null $time_contact
 */
class ClubForm extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const TIME_MORNING = 0; 
    const TIME_NOON = 1;    
    const TIME_EVENING = 2; 
    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'club_form';
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
    ];
}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'time_contact'], 'integer'],
            [['title', 'contact_phone'], 'required'],
            [['created_at', 'updated_at', 'contact_email', 'name'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 100],
            [['contact_phone'], 'string', 'max' => 25],
            ['time_contact', 'in', 'range' => array_keys(self::getTimeContactOptions())], 
            ['reCaptcha', ReCaptchaValidator2::class,
            'secret'          => Yii::$app->params['reCaptcha.secretKey'],
            'uncheckedMessage'=> 'يرجى التحقق أنك لست روبوتاً.',
        ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'status' => Yii::t('backend', 'Status'),
            'title' => Yii::t('backend', 'TitleE'),
            'contact_phone' => Yii::t('backend', 'Contact Phone'),
            'contact_email' => Yii::t('backend', 'Contact Email'),
            'name' => Yii::t('backend', 'Name'),
            'time_contact' => Yii::t('backend', 'Time Contact'),
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
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
          

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
    public  function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }
    public function clubStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">'.Yii::t('backend', 'Not Active').'</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">'.Yii::t('backend', 'Active').'</span>'
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
    public static function getTimeContactOptions()
    {
        return [
            self::TIME_MORNING => Yii::t('backend', 'صباحي'),
            self::TIME_NOON => Yii::t('backend', 'الظهيرة'),
            self::TIME_EVENING => Yii::t('backend', 'مسائي'),
        ];
    }

    /**
     * Returns the time contact label.
     *
     * @return string
     */
    public function getTimeContactLabel()
    {
        $options = self::getTimeContactOptions();
        return $options[$this->time_contact] ?? Yii::t('backend', 'غير محدد');
    }
}
