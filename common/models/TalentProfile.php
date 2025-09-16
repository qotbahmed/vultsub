<?php

namespace common\models;

use Yii;
use trntv\filekit\behaviors\UploadBehavior;
/**
 * This is the model class for table "talent_profile".
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string $password
 * @property string|null $address
 * @property string|null $country_code
 * @property int|null $age
 * @property string|null $img
 * @property string|null $img_path
 * @property string|null $img_base_url
 * @property string|null $sport_name
 * @property int|null $academy_id
 * @property int|null $status
 * @property int|null $training_type
 * @property string|null $academy_name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $bio
 * @property string|null $video_path
 * @property string|null $video_base_url
 */
class TalentProfile extends \yii\db\ActiveRecord
{

    public $img_file;
    // Training Types
    const TRAINING_TYPE_INDIVIDUAL = 1;
    const TRAINING_TYPE_ACADEMY = 2;
    public $video;
    // Status
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%talent_profile}}';
    }
    public function behaviors()
    {
        return [
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'img',
                'pathAttribute' => 'img_path',
                'baseUrlAttribute' => 'img_base_url'
            ],
            'video' => [
                'class' => UploadBehavior::class,
                'attribute' => 'video',
                'pathAttribute' => 'video_path',
                'baseUrlAttribute' => 'video_base_url'
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
  public function rules()
{
    return [
        [['name', 'password', 'email'], 'required'],
        [['age', 'status','academy_id'], 'integer'],
        [['nationality', 'facebook', 'twitter', 'instagram', 'linkedin', 'whatsapp'], 'string', 'max' => 255],

        ['slug', 'unique', 'targetClass' => self::class, 'message' => 'This slug is already taken.'],
        [['created_at', 'updated_at', 'img','video'], 'safe'],
        [['name', 'phone', 'email', 'address', 'img_path', 'img_base_url','video_base_url','video_path','phone'], 'string', 'max' => 255],
        [['bio'], 'string'],
        [['country_code'], 'string', 'max' => 5],
        [['academy_name', 'sport_name'], 'string', 'max' => 255],  
        [['status'], 'default', 'value' => self::STATUS_ACTIVE],
        [['img_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 5 * 1024 * 1024],
    ];
}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
    'id' => Yii::t('common', 'id'),
    'img' => Yii::t('common', 'img'),
    'name' => Yii::t('common', 'name'),
    'phone' => Yii::t('common', 'phone'),
    'email' => Yii::t('common', 'email'),
    'password' => Yii::t('common', 'password'),
    "sport_name" => Yii::t('common', 'Sport Name'),
    'address' => Yii::t('common', 'address'),
    'age' => Yii::t('common', 'age'),
    'img' => Yii::t('common', 'img'),
    'img_path' => Yii::t('common', 'img_path'),
    'img_base_url' => Yii::t('common', 'img_base_url'),
    'created_at' => Yii::t('common', 'created_at'),
    'updated_at' => Yii::t('common', 'updated_at'),
    'status' => Yii::t('common', 'status'),
    'academy_name' => Yii::t('common', 'Academy Name'),
    'country_code' => Yii::t('common', 'Country Code'),
    'training_type' => Yii::t('common', 'Training Type'),
    'bio' => Yii::t('common', 'Bio'),
    'video_path' => Yii::t('common', 'Video Path'),
    'video_base_url' => Yii::t('common', 'Video Base Url'),
    'facebook' => Yii::t('backend', 'Facebook'),
    'nationality' => Yii::t('backend', 'Nationality'),
'youtube' => Yii::t('backend', 'Youtube'),
'twitter' => Yii::t('backend', 'Twitter'),
'instagram' => Yii::t('backend', 'Instagram'),
'linkedin' => Yii::t('backend', 'Linkedin'),
'whatsapp' => Yii::t('backend', 'Whatsapp'),

        ];
    }

    /**
     * Get Status Options
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_NOT_ACTIVE => 'Not Active',
        ];
    }

    /**
     * Get Status Label
     * @return string
     */
    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return $options[$this->status] ?? 'Unknown';
    }
     public function getTrainingTypeLabel()
    {
        $types = self::getTrainingTypeOptions();
        return $types[$this->training_type] ?? null;
    }

    /**
     * Returns the list of training type options.
     *
     * @return array
     */
 
    public static function getTrainingTypeOptions()
{
    return [
        self::TRAINING_TYPE_INDIVIDUAL => Yii::t('common', 'Individual'),
        self::TRAINING_TYPE_ACADEMY => Yii::t('common', 'Academy'),
    ];
}

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
    public function setPassword($password)
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Check if the password has been changed or is newly set
            if ($this->isAttributeChanged('password')) {
                $this->setPassword($this->password); // Hash the password
            }
            return true;
        }
        return false;
    }
    /**
     * Load all form data
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        return $this->load($data);
    }

    /**
     * Save model with validation
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        return $this->save($runValidation, $attributeNames);
    }
    public function getVideo($default = '')
    {
        return $this->video_path
            ? rtrim(Yii::getAlias($this->video_base_url), '/') . '/' . ltrim(Yii::getAlias($this->video_path), '/')
            : $default;
    }
    public function getImg($default = '')
    {
        return $this->img_path
            ? rtrim(Yii::getAlias($this->img_base_url), '/') . '/' . ltrim(Yii::getAlias($this->img_path), '/')
            : $default;
    }
    public function getTalentImages()
{
    return $this->hasMany(TalentImages::class, ['talent_id' => 'id']);
}

}
