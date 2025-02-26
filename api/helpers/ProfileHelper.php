<?php

namespace api\helpers;

use common\models\User;
use api\helpers\ResponseHelper;

class ProfileHelper
{
    public static function instance()
    {
        return new self();
    }

    public function Delete($user)
    {
        $del_prefix = 'del_'.$this->generateKey(10).'_';   
        
        if($user)
        {   

            $user->mobile = $del_prefix.$user->mobile;
            $user->email = $del_prefix.$user->email;
            $user->username = $del_prefix.$user->username;
            $user->status = User::STATUS_DELETED;
            
            if(!$user->save()){                
                return ResponseHelper::sendFailedResponse(['MESSAGE'=> \Yii::t('frontend', 'There is an error occured please check this again')],400);
            }else{
                return ResponseHelper::sendSuccessResponse(['MESSAGE'=> \Yii::t('frontend', 'User has been deleted successfully')],200);            
            }            
        }else{
            return ResponseHelper::sendFailedResponse(['MESSAGE'=> \Yii::t('frontend', 'User not found')],400);
        }
    }


    public static function generateKey($keyLength) {
        // Set a blank variable to store the key in
        $key = "";
        for ($x = 1; $x <= $keyLength; $x++) {
            // Set each digit
            $key .= random_int(0, 9);
        }
        return $key;
    }
}