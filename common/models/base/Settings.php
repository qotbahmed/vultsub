<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use common\models\SettingsQuery;
use mootensai\relation\RelationTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;

/**
 * This is the base model class for table "settings".
 *
 * @property integer $id
 * @property string $website_title
 * @property string $phone
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
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $Vision
 * @property string $Mission
 */
class Settings extends ActiveRecord
{

    public $lock;
    use RelationTrait;


    use MultiLanguageTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            ''
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by'], 'integer'],
            [['Vision', 'Mission', 'who_we_are'], 'string'],
            [['Vision', 'Mission', 'who_we_are'], 'safe'],
            [['Talent_email', 'Talent_phone', 'Talent_address'], 'string', 'max' => 255],
            [['website_title', 'phone', 'email', 'notification_email', 'address', 'facebook', 'youtube', 'twitter', 'instagram', 'linkedin', 'whatsapp', 'app_ios', 'app_android', 'video_url', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * 
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock 
     * 
     */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'website_title' => Yii::t('backend', 'Website Title'),
            'phone' => Yii::t('backend', 'Phone'),
            'email' => Yii::t('backend', 'Email'),
            'Talent_phone' => Yii::t('backend', 'Phone'),
            'Talent_email' => Yii::t('backend', 'Email'),
            'Talent_address' => Yii::t('backend', 'Address'),
            'notification_email' => Yii::t('backend', 'Notification Email'),
            'address' => Yii::t('backend', 'Address'),
            'facebook' => Yii::t('backend', 'Facebook'),
            'youtube' => Yii::t('backend', 'Youtube'),
            'twitter' => Yii::t('backend', 'Twitter'),
            'instagram' => Yii::t('backend', 'Instagram'),
            'linkedin' => Yii::t('backend', 'Linkedin'),
            'whatsapp' => Yii::t('backend', 'Whatsapp'),
            'app_ios' => Yii::t('backend', 'App Ios'),
            'app_android' => Yii::t('backend', 'App Android'),
            'video_url' => Yii::t('backend', 'Video Url'),
            'Vision' => Yii::t('backend', 'Vision'),
            'Mission' => Yii::t('backend', 'Mission'),
            'who_we_are' => Yii::t('backend', 'who we are'),

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
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_text',

                    'attributes' => ['Vision', 'Mission'],
                    'admin_routes' => [
                        'page/update',
                        'page/index',
                    ],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => UUIDBehavior::class,
                'column' => 'id',
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return SettingsQuery the active query used by this AR class.
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Saves the model and related models if any.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     */
    public function saveAll()
    {
        return $this->save();
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
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }
    public function optimisticLock()
    {
        return null; // Disable optimistic locking
    }
}
