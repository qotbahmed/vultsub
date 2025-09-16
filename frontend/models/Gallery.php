<?php

namespace frontend\models;

use \frontend\models\base\Gallery as BaseGallery;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "gallery".
 */
class Gallery extends BaseGallery
{
    public $photos;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['photos'],'safe']

        ];
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            [
                'class' => UploadBehavior::class,
                'attribute' => 'photos',
                'multiple' => true,
                'uploadRelation' => 'galleryPhotos',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
                'orderAttribute' => 'order',
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
            ],



        ];
    }


}
