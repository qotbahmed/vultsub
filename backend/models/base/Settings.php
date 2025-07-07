<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;
use backend\models\query\SettingsQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "settings".
 *
 * @property integer $id
 * @property string $website_title
 * @property string $phone
 * @property string $email
 * @property string $points_earned_per_riyal
 * @property string $notification_email
 * @property string $address
 * @property string $facebook
 * @property string $youtube
 * @property string $twitter
 * @property string $instagram
 * @property string $linkedin
 * @property string $whatsapp
 * @property string $app_ios
 * @property string $app_android
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Settings extends ActiveRecord
{

    use RelationTrait;

    /**
     * @inheritdoc
     */


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'whatsapp' => Yii::t('backend', 'Whatsapp Number'),
            'id' => Yii::t('backend', 'ID'),
            'website_title' => Yii::t('backend', 'Website Title'),
            'phone' => Yii::t('backend', 'Phone'),
            'email' => Yii::t('backend', 'Email'),
            'notification_email' => Yii::t('backend', 'Notification Email'),
            'address' => Yii::t('backend', 'Address'),
            'facebook' => Yii::t('backend', 'Facebook'),
            'youtube' => Yii::t('backend', 'Youtube'),
            'twitter' => Yii::t('backend', 'Twitter'),
            'instagram' => Yii::t('backend', 'Instagram'),
            'linkedin' => Yii::t('backend', 'Linkedin'),
            'app_ios' => Yii::t('backend', 'App Ios'),
            'app_android' => Yii::t('backend', 'App Android'),
            'video_url' => Yii::t('backend', 'Intro Video'),
            'min_charge_nurse' => Yii::t('backend', 'Nurse Minimum Charge'),
            'min_charge_nanny' => Yii::t('backend', 'Nanny Minimum Charge'),
            'min_charge_orderly' => Yii::t('backend', 'Orderly Minimum Charge'),
            'distance_range' => Yii::t('backend', 'Distance Range'),
            'service_fees' => Yii::t('backend', 'Service Fees'),
            'taxes' => Yii::t('backend', 'Taxes'),
            'rush_hour_rate' => Yii::t('backend', 'Rush Hour Rate'),
            'rush_hour_from' => Yii::t('backend', 'Rush Hour From'),
            'rush_hour_to' => Yii::t('backend', 'Rush Hour To'),
            'service_fee_type' => Yii::t('backend', 'Taxes Type'),
            ' taxes_type' => Yii::t('backend', 'Service Fees Type'),
            'period' => Yii::t('backend', 'Period Schedule (Day)'),
            'end_session_alarm' => Yii::t('backend', 'End Session Alarm (Minute)'),
            'onway_btn_visible' => Yii::t('backend', 'Show the "On my way" button (Minutes)'),
            'refund_policy_period' => Yii::t('backend', 'Refund policy period (Hours)'),
            'points_per_second' => Yii::t('backend', 'Points per second'),
            'reading_points_delay' => Yii::t('backend', 'Reading points delay'),
            'points_earned_per_riyal' => Yii::t('backend', 'Points earned per riyal'),
            'max_daily_points_per_user' => Yii::t('backend', 'Maximum points collected daily for each person'),
            'daily_points' => Yii::t('backend', 'Points for the day'),
            'visa' => Yii::t('backend', 'Show Pay With Visa'),
            'mada' => Yii::t('backend', 'Show Pay With Mada'),
            'applepay' => Yii::t('backend', 'Show Pay With Applepay'),

        ];
    }

    public static function serviceFees()
    {
        return [
            1 => Yii::t('backend', 'Percentage'),
            2 => Yii::t('backend', 'Fixed Amount')];

    }

    /**
     * @inheritdoc
     * @return SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }
}
