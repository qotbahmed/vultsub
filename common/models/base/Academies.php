<?php


namespace common\models\base;

use common\models\Package;
use common\models\query\AcademiesQuery;
use common\models\Subscription;
use common\models\Cities;
use common\models\Districts;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\db\ActiveRecord;
//use app\models\AcademiesQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "academies".
 *
 * @property string $id
 * @property string $title
 * @property string $contact_phone
 * @property string $description
 * @property string $contact_email
 * @property string $logo_path
 * @property string $logo_base_url
 * @property string $address
 * @property string $location
 * @property string $lng
 * @property string $lat
 * @property integer $manager_id
 * @property string $parent_id
 * @property integer $main
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $primary_color
 * @property string $secondary_color
 * @property string $accent_color
 * @property string $sport_icons
 * @property string $tax_number
 * @property string $commercial_registration_number
 * @property string $password
 * @property string $days
 * @property string $slug
 * @property string $startTime
 * @property string $endTime
 * @property string $extraPhone
 * @property string $district
 * @property string $qr_login_base_url
 * @property string $qr_login_path
 * @property int $status
 * @property string $qr_link
 * @property string $qr_path
 * @property string $qr_base_url
 * @property string $qr_unified_link
 * @property string $qr_unified_path
 * @property string $qr_unified_base_url
 * @property int $complete_profile
 * @property float $total_rating
 * @property int $mobile_notifications_enabled
 * @property int $count_rate
 * @property \common\models\Academies $parent
 * @property \common\models\Academies[] $academies
 * @property \common\models\User $manager
 * 
 * 
 * 
 */
class Academies extends ActiveRecord
{
    use RelationTrait;
    const MAIN_VALUE = 1;
    public $image;

    public $manager_fullname;
    public $manager_email;
    public  $manager_phone;
    public $manager_password;
    public $stamp_image_upload;
    public $signature_image_upload;

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    const MOBILE_NOTIFICATIONS_ENABLED = 1;
    const MOBILE_NOTIFICATIONS_DISABLED = 0;
    public static function getStatusLabels()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Inactive'),
        ];
    }

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'parent',
            'academies',
            'manager'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string', 'min' => 3, 'max' => 100],
            [['title', 'startTime', 'endTime', 'contact_phone', 'contact_email', 'days'], 'required'],

            [
                ['contact_phone', 'manager_phone'],
                'match',
                'pattern' => '/^((009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})|(\+2|002|2)(10|11|12|15)([\d]{8})|(0)(10|11|12|15)([\d]{8}))$/',
                'skipOnEmpty' => true
            ],
            ['mobile_notifications_enabled', 'default', 'value' => self::MOBILE_NOTIFICATIONS_ENABLED],
            ['mobile_notifications_enabled', 'integer'],
            ['mobile_notifications_enabled', 'in', 'range' => [self::MOBILE_NOTIFICATIONS_DISABLED, self::MOBILE_NOTIFICATIONS_ENABLED]],
            ['status', 'integer'],
            ['status', 'in', 'range' => [0, 1]],
            [['description', 'district'], 'string'],
            [['qr_login_base_url', 'qr_login_path', 'qr_link', 'qr_path', 'qr_base_url', 'bio'], 'string'],
            [['manager_id', 'parent_id', 'created_by', 'updated_by', 'complete_profile'], 'integer'],
            [['created_at', 'updated_at', 'manager_id', 'image', 'startTime', 'endTime', 'slug'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['contact_email', 'manager_email'], 'email'],
            [['contact_phone', 'manager_phone', 'report_notification_phone'], 'string', 'max' => 25],
            [['contact_email', 'logo_path', 'logo_base_url', 'address', 'location', 'lng', 'lat', 'manager_fullname'], 'string', 'max' => 255],
            [['main'], 'integer', 'max' => 1],
            [['primary_color', 'secondary_color', 'accent_color', 'password', 'manager_password'], 'string', 'max' => 255],
            [['sport_icons'], 'string'],
            ['manager_email', 'unique', 'targetClass' => \common\models\User::className(), 'targetAttribute' => 'email', 'message' => Yii::t('backend', 'This email address has already been taken.')],
            ['manager_phone', 'unique', 'targetClass' => \common\models\User::className(), 'targetAttribute' => 'mobile', 'message' => Yii::t('backend', 'This mobile number has already been taken.')],
            [['title', 'contact_phone', 'contact_email'], 'unique'],
            [
                ['extraPhone'],
                'match',
                'pattern' => '/^((009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})|(\+2|002|2)(10|11|12|15)([\d]{8})|(0)(10|11|12|15)([\d]{8}))$/',
                'skipOnEmpty' => true
            ],
            //  ['manager_id', 'required'],

            [['tax_number', 'commercial_registration_number', 'add_tax'], 'required', 'when' => function ($model) {
                return Yii::$app->controller->action->id === 'setting' && Yii::$app->controller->MainAcadmin;
            }],
            [['tax_number', 'commercial_registration_number'], 'string'],
            [['tax_number', 'commercial_registration_number'], 'unique', 'when' => function ($model) {
                return Yii::$app->controller->action->id === 'setting';
            }],
            ['tax_number', 'match', 'pattern' => '/^\d{15}$/', 'message' => Yii::t('common', 'Tax number must be numeric and exactly 15 digits'), 'when' => function ($model) {
                return Yii::$app->controller->action->id === 'setting';
            }],
            ['commercial_registration_number', 'match', 'pattern' => '/^\d{10}$/', 'message' => Yii::t('common', 'Commercial registration number must be numeric and exactly 10 digits'), 'when' => function ($model) {
                return Yii::$app->controller->action->id === 'setting';
            }],
            [
                ['add_tax'],
                'number',
                'min' => 0.01,
                'message' => Yii::t('common', 'The added tax must be a positive number greater than zero'),
                'when' => function ($model) {
                    return Yii::$app->controller->action->id === 'setting' && Yii::$app->controller->MainAcadmin;
                }
            ],
            [['stamp_image_upload', 'signature_image_upload'], 'safe'],
            [['stamp_logo_base_url', 'signature_logo_base_url'], 'string', 'max' => 255],
            [['stamp_image', 'signature_image'], 'string', 'max' => 255],
            [['facebook', 'youtube', 'twitter', 'instagram', 'linkedin', 'whatsapp'], 'string', 'max' => 255],
            [['total_rating', 'count_rate'], 'number'],
            [['city_id', 'district_id'], 'integer'],
            [['city_id', 'district_id'], 'default', 'value' => null],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'academies';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Academy Title'),
            'image' => Yii::t('backend', 'Image'),
            'contact_phone' => Yii::t('backend', 'Contact Phone'),
            'description' => Yii::t('backend', 'Description'),
            'contact_email' => Yii::t('backend', 'Contact Email'),
            'logo_path' => Yii::t('backend', 'Logo Path'),
            'logo_base_url' => Yii::t('backend', 'Logo Base Url'),
            'address' => Yii::t('backend', 'Address'),
            'location' => Yii::t('backend', 'Location'),
            'lng' => Yii::t('backend', ''),
            'lat' => Yii::t('backend', ''),
            'manager_id' => Yii::t('backend', 'Manager'),
            'parent_id' => Yii::t('backend', 'Main branch'),
            'main' => Yii::t('backend', 'Main'),
            'primary_color' => Yii::t('backend', 'Primary Color'),
            'secondary_color' => Yii::t('backend', 'Secondary Color'),
            'accent_color' => Yii::t('common', 'Accent Color'),
            'sport_icons' => Yii::t('backend', 'Sport Icons'),
            'tax_number' => Yii::t('backend', 'Tax Number'),
            'commercial_registration_number' => Yii::t('backend', 'Commercial Registration Number'),
            'password' => Yii::t('backend', 'Password'),
            'days' => Yii::t('backend', 'Days'),
            'startTime' => Yii::t('backend', 'Start Time'),
            'endTime' => Yii::t('backend', 'End Time'),
            'extraPhone' => Yii::t('backend', 'Extra Phone'),
            'district' => Yii::t('backend', 'District'),
            'city_id' => Yii::t('backend', 'city'),
            'district_id' => Yii::t('backend', 'district'),
            'mobile_notifications_enabled' => Yii::t('backend', 'Mobile Notifications'),
            'report_notification_phone' => Yii::t('backend', 'Daily Report Notification Phone'),
            'manager_email' => Yii::t('backend', 'Email'),
            'manager_password' => Yii::t('backend', 'Password'),
            'manager_fullname' => Yii::t('backend', 'Full Name'),
            'manager_phone' => Yii::t('backend', 'Mobile'),
            'add_tax' => Yii::t('backend', 'Add Tax'),
            'bio' => Yii::t('backend', 'Biography'),
            'facebook' => Yii::t('backend', 'Facebook'),
            'youtube' => Yii::t('backend', 'YouTube'),
            'twitter' => Yii::t('backend', 'Twitter'),
            'instagram' => Yii::t('backend', 'Instagram'),
            'linkedin' => Yii::t('backend', 'LinkedIn'),
            'whatsapp' => Yii::t('backend', 'WhatsApp'),
            'total_rating' => Yii::t('backend', 'Total Rating'),
            'signature_image_upload' => Yii::t('backend', 'Signature Image Upload'),
            'stamp_image_upload' => Yii::t('backend', 'Stamp Image Upload'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\common\models\Academies::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademies()
    {
        return $this->hasMany(\common\models\Academies::className(), ['parent_id' => 'id']);
    }

    public function getPackages()
    {
        return $this->hasMany(Package::className(), ['academy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'manager_id']);
    }

    public function getLogo($default = '')
    {
        return $this->logo_path
            ? rtrim(Yii::getAlias($this->logo_base_url), '/') . '/' . ltrim(Yii::getAlias($this->logo_path), '/')
            : $default;
    }

    /**
     * @inheritdoc
     * @return array
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

            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'logo_path',
                'baseUrlAttribute' => 'logo_base_url'
            ],
            'stampImageUploadBehavior' => [
                'class' => UploadBehavior::class,
                'attribute' => 'stamp_image_upload',
                'pathAttribute' => 'stamp_image',
                'baseUrlAttribute' => 'stamp_logo_base_url',
            ],
            'signatureImageUploadBehavior' => [
                'class' => UploadBehavior::class,
                'attribute' => 'signature_image_upload',
                'pathAttribute' => 'signature_image',
                'baseUrlAttribute' => 'signature_logo_base_url',
            ],

        ];
    }

    /**
     * @inheritdoc
     * @return AcademiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AcademiesQuery(get_called_class());
    }

    /*
     * Saves the model and related models if any.
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
    public function getStampImageUrl()
    {
        if ($this->stamp_logo_base_url && $this->stamp_image) {
            return rtrim($this->stamp_logo_base_url, '/') . '/' . ltrim($this->stamp_image, '/');
        }
        return null;
    }

    public function getSignatureImageUrl()
    {
        if ($this->signature_logo_base_url && $this->signature_image) {
            return rtrim($this->signature_logo_base_url, '/') . '/' . ltrim($this->signature_image, '/');
        }
        return null;
    }
    public function getActiveSubscriptions()
    {
        return Subscription::find()
            ->where([
                'academy_id' => $this->id, // Academy ID matches the current academy
                'subscription_status' => 0  // Subscription is active
            ])
            ->count();
    }

    public function getAcademySport()
    {
        return $this->hasMany(\common\models\AcademySport::class, ['academy_id' => 'id']);
    }
    public function getSports()
    {
        return $this->hasMany(\common\models\Sport::className(), ['id' => 'sport_id'])
            ->viaTable('academy_sport', ['academy_id' => 'id']);
    }
    public static function getMobileNotificationsLabels()
    {
        return [
            self::MOBILE_NOTIFICATIONS_ENABLED => Yii::t('backend', 'Enabled'),
            self::MOBILE_NOTIFICATIONS_DISABLED => Yii::t('backend', 'Disabled'),
        ];
    }
}
