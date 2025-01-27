<?php

namespace common\models;

use \common\models\base\SmsLog as BaseSmsLog;

/**
 * This is the model class for table "sms_log".
 */
class SmsLog extends BaseSmsLog
{
    const STATUS_NEW = 0;
    const STATUS_REGISTERED = 1;

    const TYPE_REGISTER = 0;



//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return array_replace_recursive(parent::rules(),
//	    []);
//    }
	
}
