<?php

namespace common\models;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;

/**
 * This is the model class for table "announcement".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $image_path
 * @property string|null $image_base_url
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Disclaimers extends \yii\db\ActiveRecord
{
    public $img;
    public $team_ids; // Array for multiple team selection
    
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
       
        return 'disclaimers';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'fileUploadBehavior' => [
                'class' => UploadBehavior::class,
                'attribute' => 'img',
                'pathAttribute' => 'image_path',
                'baseUrlAttribute' => 'image_base_url',
            ],
        ];
    }
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['team_id'], 'integer'],
            [['team_ids'], 'each', 'rule' => ['integer']],
            [['description'], 'string'],
            [['status', 'created_by', 'updated_by','academy_id'], 'integer'],
            [['created_at', 'updated_at','img'], 'safe'],
            [['title', 'image_path', 'image_base_url'], 'string', 'max' => 255],
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
            'description' => Yii::t('common', 'Description'),
            'team_id' => Yii::t('common', 'Team'),
            'team_ids' => Yii::t('common', 'Teams'),
            'image_path' => Yii::t('common', 'Image Path'),
            'image_base_url' => Yii::t('common', 'Image Base Url'),
            'img' => Yii::t('common', 'Img'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Inactive'),
        ];
    }
    public function getStatusLabel()
    {
        return self::getStatusOptions()[$this->status] ?? Yii::t('common', 'Unknown');
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // If team_ids is provided and team_id is not set (only for new records)
            if ($insert && !empty($this->team_ids) && is_array($this->team_ids) && empty($this->team_id)) {
                $this->team_id = $this->team_ids[0];
            }
            
            // Set timestamps
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->created_by = Yii::$app->user->id ?? null;
            } else {
                $this->updated_at = date('Y-m-d H:i:s');
                $this->updated_by = Yii::$app->user->id ?? null;
            }
            
            return true;
        }
        return false;
    }
    
    public function loadAll($data)
    {
        $result = $this->load($data);
        
        // This logic is no longer needed since we handle it in the form
        
        return $result;
    }
    
    public function saveAll($runValidation = true, $attributeNames = null)
    {
        // If team_ids is provided, create multiple disclaimers
        if (!empty($this->team_ids) && is_array($this->team_ids)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $savedCount = 0;
                foreach ($this->team_ids as $teamId) {
                    // Create a new disclaimer for each team
                    $disclaimer = new self();
                    $disclaimer->title = $this->title;
                    $disclaimer->description = $this->description;
                    $disclaimer->team_id = $teamId;
                    $disclaimer->academy_id = $this->academy_id;
                    $disclaimer->status = $this->status ?? self::STATUS_ACTIVE;
                    $disclaimer->created_by = Yii::$app->user->id;
                    $disclaimer->created_at = date('Y-m-d H:i:s');
                    $disclaimer->img = $this->img;
                    
                    if ($disclaimer->save($runValidation, $attributeNames)) {
                        $savedCount++;
                    } else {
                        throw new \Exception('Failed to save disclaimer for team ID: ' . $teamId);
                    }
                }
                
                $transaction->commit();
                return $savedCount > 0;
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error('Failed to save multiple disclaimers: ' . $e->getMessage());
                return false;
            }
        }
        
        // Default single save
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
    
    /**
     * Gets query for [[Team]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(MainScheduleTeams::class, ['id' => 'team_id']);
    }
    
    /**
     * Get team name
     * @return string
     */
    public function getTeamName()
    {
        return $this->team ? $this->team->getTeamName() : '';
    }
}
