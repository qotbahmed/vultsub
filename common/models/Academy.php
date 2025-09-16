<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "academies".
 *
 * @property int $id
 * @property string|null $slug
 * @property string $title
 * @property string|null $contact_phone
 * @property string|null $description
 * @property string|null $contact_email
 * @property string|null $logo_path
 * @property string|null $logo_base_url
 * @property string|null $address
 * @property string|null $location
 * @property int|null $city_id
 * @property int|null $district_id
 * @property string|null $lat
 * @property string|null $lng
 * @property int|null $manager_id
 * @property int|null $parent_id
 * @property int|null $main
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $days
 * @property string|null $startTime
 * @property string|null $endTime
 * @property string|null $extraPhone
 * @property string|null $district
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $sport_icons
 * @property string|null $tax_number
 * @property string|null $commercial_registration_number
 * @property string|null $password
 * @property float $add_tax
 * @property int $status
 * @property string|null $qr_login_path
 * @property string|null $qr_login_base_url
 * @property string|null $qr_link
 * @property string|null $qr_path
 * @property string|null $qr_base_url
 * @property int $complete_profile
 * @property string|null $bio
 * @property string|null $facebook
 * @property string|null $youtube
 * @property string|null $twitter
 * @property string|null $instagram
 * @property string|null $linkedin
 * @property string|null $whatsapp
 * @property float $total_rating
 * @property int $count_rate
 * @property string|null $stamp_image
 * @property string|null $stamp_logo_base_url
 * @property string|null $signature_image
 * @property string|null $signature_logo_base_url
 * @property int $mobile_notifications_enabled
 * @property string|null $report_notification_phone
 * @property string|null $qr_unified_link
 * @property string|null $qr_unified_path
 * @property string|null $qr_unified_base_url
 * @property int|null $vult_request_id
 * @property string|null $subscription_plan
 * @property string|null $subscription_status
 * @property string|null $subscription_start
 * @property string|null $subscription_end
 * @property int|null $trial_start
 * @property int|null $trial_end
 * @property string|null $trial_status
 * @property float $monthly_revenue
 *
 * @property Academy $parent
 * @property Academy[] $academies
 * @property City $city
 * @property District $district
 * @property User $manager
 * @property AcademyRequest $vultRequest
 */
class Academy extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academies';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'bio', 'sport_icons', 'qr_unified_link'], 'string'],
            [['city_id', 'district_id', 'manager_id', 'parent_id', 'main', 'created_by', 'updated_by', 'complete_profile', 'count_rate', 'mobile_notifications_enabled', 'vult_request_id'], 'integer'],
            [['lat', 'lng'], 'string'],
            [['add_tax', 'total_rating', 'monthly_revenue'], 'number'],
            [['startTime', 'endTime'], 'safe'],
            [['status'], 'boolean'],
            [['slug', 'title', 'contact_phone', 'contact_email', 'logo_path', 'logo_base_url', 'address', 'location', 'days', 'extraPhone', 'district', 'primary_color', 'secondary_color', 'tax_number', 'commercial_registration_number', 'password', 'qr_login_path', 'qr_login_base_url', 'qr_link', 'qr_path', 'qr_base_url', 'facebook', 'youtube', 'twitter', 'instagram', 'linkedin', 'whatsapp', 'stamp_image', 'stamp_logo_base_url', 'signature_image', 'signature_logo_base_url', 'report_notification_phone', 'qr_unified_path', 'qr_unified_base_url', 'subscription_plan', 'subscription_status', 'subscription_start', 'subscription_end', 'trial_status'], 'string', 'max' => 255],
            [['qr_unified_path', 'qr_unified_base_url'], 'string', 'max' => 500],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Academy::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::class, 'targetAttribute' => ['district_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['manager_id' => 'id']],
            [['vult_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademyRequest::class, 'targetAttribute' => ['vult_request_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'slug' => Yii::t('common', 'Slug'),
            'title' => Yii::t('common', 'Title'),
            'contact_phone' => Yii::t('common', 'Contact Phone'),
            'description' => Yii::t('common', 'Description'),
            'contact_email' => Yii::t('common', 'Contact Email'),
            'logo_path' => Yii::t('common', 'Logo Path'),
            'logo_base_url' => Yii::t('common', 'Logo Base Url'),
            'address' => Yii::t('common', 'Address'),
            'location' => Yii::t('common', 'Location'),
            'city_id' => Yii::t('common', 'City ID'),
            'district_id' => Yii::t('common', 'District ID'),
            'lat' => Yii::t('common', 'Lat'),
            'lng' => Yii::t('common', 'Lng'),
            'manager_id' => Yii::t('common', 'Manager ID'),
            'parent_id' => Yii::t('common', 'Parent ID'),
            'main' => Yii::t('common', 'Main'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'days' => Yii::t('common', 'Days'),
            'startTime' => Yii::t('common', 'Start Time'),
            'endTime' => Yii::t('common', 'End Time'),
            'extraPhone' => Yii::t('common', 'Extra Phone'),
            'district' => Yii::t('common', 'District'),
            'primary_color' => Yii::t('common', 'Primary Color'),
            'secondary_color' => Yii::t('common', 'Secondary Color'),
            'sport_icons' => Yii::t('common', 'Sport Icons'),
            'tax_number' => Yii::t('common', 'Tax Number'),
            'commercial_registration_number' => Yii::t('common', 'Commercial Registration Number'),
            'password' => Yii::t('common', 'Password'),
            'add_tax' => Yii::t('common', 'Add Tax'),
            'status' => Yii::t('common', 'Status'),
            'qr_login_path' => Yii::t('common', 'Qr Login Path'),
            'qr_login_base_url' => Yii::t('common', 'Qr Login Base Url'),
            'qr_link' => Yii::t('common', 'Qr Link'),
            'qr_path' => Yii::t('common', 'Qr Path'),
            'qr_base_url' => Yii::t('common', 'Qr Base Url'),
            'complete_profile' => Yii::t('common', 'Complete Profile'),
            'bio' => Yii::t('common', 'Bio'),
            'facebook' => Yii::t('common', 'Facebook'),
            'youtube' => Yii::t('common', 'Youtube'),
            'twitter' => Yii::t('common', 'Twitter'),
            'instagram' => Yii::t('common', 'Instagram'),
            'linkedin' => Yii::t('common', 'Linkedin'),
            'whatsapp' => Yii::t('common', 'Whatsapp'),
            'total_rating' => Yii::t('common', 'Total Rating'),
            'count_rate' => Yii::t('common', 'Count Rate'),
            'stamp_image' => Yii::t('common', 'Stamp Image'),
            'stamp_logo_base_url' => Yii::t('common', 'Stamp Logo Base Url'),
            'signature_image' => Yii::t('common', 'Signature Image'),
            'signature_logo_base_url' => Yii::t('common', 'Signature Logo Base Url'),
            'mobile_notifications_enabled' => Yii::t('common', 'Mobile Notifications Enabled'),
            'report_notification_phone' => Yii::t('common', 'Report Notification Phone'),
            'qr_unified_link' => Yii::t('common', 'Qr Unified Link'),
            'qr_unified_path' => Yii::t('common', 'Qr Unified Path'),
            'qr_unified_base_url' => Yii::t('common', 'Qr Unified Base Url'),
            'vult_request_id' => Yii::t('common', 'Vult Request ID'),
            'subscription_plan' => Yii::t('common', 'Subscription Plan'),
            'subscription_status' => Yii::t('common', 'Subscription Status'),
            'subscription_start' => Yii::t('common', 'Subscription Start'),
            'subscription_end' => Yii::t('common', 'Subscription End'),
            'trial_start' => Yii::t('common', 'Trial Start'),
            'trial_end' => Yii::t('common', 'Trial End'),
            'trial_status' => Yii::t('common', 'Trial Status'),
            'monthly_revenue' => Yii::t('common', 'Monthly Revenue'),
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Academy::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Academies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademies()
    {
        return $this->hasMany(Academy::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[District]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::class, ['id' => 'district_id']);
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(User::class, ['id' => 'manager_id']);
    }

    /**
     * Gets query for [[VultRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVultRequest()
    {
        return $this->hasOne(AcademyRequest::class, ['id' => 'vult_request_id']);
    }
}
