<?php

namespace frontend\models;

use \frontend\models\base\Settings as BaseSettings;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the base model class for table "settings".
 *
 * @property integer $id
 * @property string $website_title
 * @property string $phone
 * // * @property string $phone2
 * @property string $email
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
 * @property string $video_url
 * @property string $created_at
 * @property string $updated_at
 * @property string $points_earned_per_riyal
 * @property integer $created_by
 * @property integer $points_per_second
 * @property integer $daily_points
 * @property integer $reading_points_delay
 * @property integer $max_daily_points_per_user
 * @property integer $updated_by
 * @property double $distance_range
 * @property double $min_charge_nanny
 * @property double $min_charge_nurse
 * @property double $min_charge_orderly
 * @property double $service_fees
 * @property double $taxes
 * @property double $onway_btn_visible
 * @property double $refund_policy_period
 * @property integer $taxes_type
 * @property integer $period
 * @property integer $end_session_alarm
 * @property integer $rush_hour_rate
 * @property integer $rush_hour_rate_type
 * @property string $rush_hour_from
 * @property string $rush_hour_to
 * @property integer $applepay
 * @property integer $mada
 * @property integer $visa
 */
class Settings extends BaseSettings
{
    use MultiLanguageTrait;

    const SERVICE_FEE_TYPE_FIXED = 2;
    const SERVICE_FEE_TYPE_PERCENT = 1;

    public function rules()
    {
        return [
            [['created_by', 'updated_by',
                'points_per_second','daily_points',
                'reading_points_delay','points_earned_per_riyal', 'max_daily_points_per_user'
            ], 'integer'],
            [['website_title', 'phone', 'email', 'notification_email', 'address',
                'facebook', 'youtube', 'twitter', 'instagram', 'linkedin', 'whatsapp',
                'app_ios', 'app_android', 'video_url', 'created_at', 'updated_at',
                'period', 'end_session_alarm'], 'safe']
        ];
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
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_text',
                    'attributes' => ['address', 'website_title'],
                    'admin_routes' => [
                        'settings/update',
                        'settings/index',
                    ],
                ],
            ],
        ];
    }

}
