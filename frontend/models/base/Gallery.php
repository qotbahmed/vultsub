<?php

namespace frontend\models\base;

use Yii;
use yii\db\ActiveRecord;
use backend\models\query\GalleryQuery;
use mootensai\relation\RelationTrait;

/**
 * This is the base model class for table "gallery".
 *
 * @property integer $id
 * @property string $title
 * @property integer $sort
 *
 * @property \backend\models\GalleryPhotos[] $galleryPhotos
 */
class Gallery extends ActiveRecord
{

    use RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [
            'galleryPhotos'
        ];
    }



    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'sort' => Yii::t('backend', 'Sort'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryPhotos()
    {
        return $this->hasMany(\backend\models\GalleryPhotos::className(), ['gallery_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GalleryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GalleryQuery(get_called_class());
    }
}
