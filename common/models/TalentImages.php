<?php

namespace common\models;
use common\models\TalentProfile;

use Yii;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "talent_images".
 *
 * @property int $id
 * @property int $talent_id
 * @property string $img
 * @property string $img_path
 * @property string $img_base_url
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property TalentProfile $talent
 */
class TalentImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'talent_images';
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
          
            
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['talent_id'], 'required'],
            [['talent_id','academy_id'], 'integer'],
            [['img'], 'safe'],
            [['img_path', 'img_base_url', 'created_at', 'updated_at','img_path', 'img_base_url'], 'string', 'max' => 255],
            [['talent_id'], 'exist', 'skipOnError' => true, 'targetClass' => TalentProfile::class, 'targetAttribute' => ['talent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'talent_id' => Yii::t('common', 'Talent Name'),
            'img' => Yii::t('common', 'Img'),
            'img_path' => Yii::t('common', 'Img Path'),
            'img_base_url' => Yii::t('common', 'Img Base Url'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Talent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function gettalentProfile()
    {
        return $this->hasOne(TalentProfile::class, ['id' => 'talent_id']);
    }


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
    
}
