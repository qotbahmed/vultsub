<?php

namespace common\models;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "sport".
 *
 * @property int $id
 * @property string $title
 * @property int|null $status
 * @property int|null $added_by
 * @property string|null $icon_base_url
 * @property string|null $icon_path
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $color
 * @property int $is_team 
 * @property string|null $title_en


 *
 * @property SportSkill[] $sportSkills
 */
class Sport extends ActiveRecord
{
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const IS_TEAM_NO = 0;
    const IS_TEAM_YES = 1;

    /**
     * @var UploadedFile
     */
    public $image;

    private $oldIconPath;
    private $oldIconBaseUrl;
    
    /**
     * Magic method to handle property access
     * This ensures that when 'title' is accessed, it automatically returns the localized version
     * 
     * @param string $name Name of the property being accessed
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'title' && Yii::$app->language === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        return parent::__get($name);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sport';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'title_en','is_team'], 'required'],
            [['status', 'added_by', 'is_team'], 'integer'],
            [['image'], 'safe'],
            [['title', 'created_at', 'updated_at', 'icon_base_url', 'icon_path', 'color', 'title_en'], 'string', 'max' => 255],
            [['title'], 'unique', 'message' => Yii::t('common', 'This title has already been taken.')],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'status' => Yii::t('common', 'Status'),
            'added_by' => Yii::t('common', 'Added By'),
            'icon_base_url' => Yii::t('common', 'Icon Base URL'),
            'icon_path' => Yii::t('common', 'Icon Path'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'imageFile' => Yii::t('common', 'Icon File'),
            'color' => Yii::t('common', 'Color'),
            'is_team' => Yii::t('common', 'Is Team Activity'),
            'title_en' => Yii::t('common', 'English Title'),


        ];
    }

    /**
     * Returns the localized title based on the current application language
     * 
     * @return string The sport title in the current language
     */
    public function getLocalizedTitle()
    {
        return Yii::$app->language === 'en' && !empty($this->title_en) 
            ? $this->title_en 
            : $this->title;
    }

    /**
     * {@inheritdoc}
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
            'image' => [
                'class' => UploadBehavior::class,
                'attribute' => 'image',
                'pathAttribute' => 'icon_path',
                'baseUrlAttribute' => 'icon_base_url',
            ],
        ];
    }

    /**
     * Returns the URL of the uploaded image.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->icon_base_url . '/' . $this->icon_path;
    }

    /**
     * Uploads the image file to the specified directory with a unique name.
     *
     * @return bool whether the upload was successful
     */
    public function uploadImage()
    {
        $uploadDir = Yii::getAlias('@webroot') . '/uploads/';

        // Check if the uploads directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        $this->icon_base_url = Yii::$app->request->baseUrl . '/uploads/';
        $this->icon_path = $this->imageFile->baseName . '_' . time() . '.' . $this->imageFile->extension;
        $filePath = $uploadDir . $this->icon_path;

        return $this->imageFile->saveAs($filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->oldIconPath = $this->icon_path;
        $this->oldIconBaseUrl = $this->icon_base_url;
    }

    /**
     * Gets query for [[SportSkills]].
     *
     * @return ActiveQuery
     */
    public function getSportSkills()
    {
        return $this->hasMany(SportSkill::class, ['sport_id' => 'id']);
    }

    /**
     * Gets query for related AcademySports.
     *
     * @return ActiveQuery
     */
    public function getAcademySports(): ActiveQuery
    {
        return $this->hasMany(AcademySport::class, ['sport_id' => 'id']);
    }

    /**
     * Loads all related models.
     *
     * @param array $data
     * @return bool
     */
    public function loadAll($data)
    {
        $success = $this->load($data);
        $skills = Yii::$app->request->post('SportSkill', []);
        $sportSkills = [];

        foreach ($skills as $index => $skillData) {
            $skill = new SportSkill();
            if ($skill->load($skillData, '') && !empty($skill->title)) {
                $sportSkills[] = $skill;
            }
        }

        $this->populateRelation('sportSkills', $sportSkills);
        return $success;
    }

    public function getSubscriptionDetails()
    {
        return $this->hasMany(SubscriptionDetails::class, ['sports_id' => 'id']);
    }

    /**
     * Saves all related models.
     *
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return bool
     * @throws Exception
     */
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$this->save($runValidation, $attributeNames)) {
                $transaction->rollBack();
                return false;
            }

            // Delete old sport skills
            SportSkill::deleteAll(['sport_id' => $this->id]);

            // Save new sport skills
            foreach ($this->sportSkills as $skill) {
                $skill->sport_id = $this->id;
                if (!$skill->save($runValidation)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Deletes the model and related models if any.
     *
     * @return bool Whether the deletion was successful.
     * @throws Exception
     */
    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($this->sportSkills as $skill) {
                if (!$skill->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Returns the status text.
     *
     * @return string
     */
    public function getStatusText()
    {
        return $this->status == self::STATUS_ACTIVE ? Yii::t('backend', 'Active') : Yii::t('backend', 'Inactive');
    }

    /**
     * Returns labels for is_team values.
     * @return array
     */
    public static function getIsTeamOptions()
    {
        return [
            self::IS_TEAM_NO => Yii::t('common', 'Individual Activity'),
            self::IS_TEAM_YES => Yii::t('common', 'Team Activity'),
        ];
    }

    /**
     * Get label text of current is_team value.
     * @return string
     */
    public function getIsTeamText()
    {
        $list = self::getIsTeamOptions();
        return $list[$this->is_team] ?? Yii::t('common', 'Unknown');
    }
}
