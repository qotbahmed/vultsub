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
public static function logOtpAttempt($mobile, $otp, $userId = null)
{
    $smsLog = new self();
    $smsLog->mobile = (string)$mobile;
    $smsLog->otp = $otp;
    $smsLog->type = self::TYPE_REGISTER;
    $smsLog->expire_at = (string)(time() + 300); 
    $smsLog->status = self::STATUS_NEW;
    $smsLog->created_at = time();
    $smsLog->created_by = $userId;
    $smsLog->save(false);
    return $smsLog;
}

public static function isOtpValid($mobile, $otp)
{
    return self::find()
        ->where(['mobile' => $mobile, 'otp' => $otp, 'status' => self::STATUS_NEW])
        ->andWhere(['>', 'expire_at', time()])
        ->orderBy(['created_at' => SORT_DESC])
        ->one();
}
public function markAsRegistered($userId)
{
    $this->status = self::STATUS_REGISTERED;
    $this->user_id = $userId;
    $this->updated_at = time();
    $this->save(false);
}

public static function exceededDailyLimit($mobile, $limit = 10)
{
    return self::find()
        ->where(['mobile' => $mobile])
        ->andWhere(['between', 'created_at', strtotime('today'), strtotime('tomorrow') - 1])
        ->count() >= $limit;
}
}
