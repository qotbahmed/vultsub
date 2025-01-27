<?php

namespace backend\models;

use \backend\models\base\ContactUs as BaseContactUs;

/**
 * This is the model class for table "contact_us".
 */
class ContactUs extends BaseContactUs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','message','email'], 'required'],
            [['message','name'], 'string'],
            [['name', 'phone', 'email', 'title', 'created_at', 'updated_at'], 'string', 'max' => 255],
            ['phone','number'],

            ['email','email'],
            ['name', 'unique', 'targetAttribute' => ['email','title','message'] ,
                'message'=>\Yii::t("frontend","Can not send the same message twice")],

        ];
    }
	
}
