<?php

namespace common\helpers;

use backend\models\Settings;
use common\models\EmailValidator;
use Yii;

class CommonHelper
{
    const STATUS_PUBLISHED = 1;
    const STATUS_NOT_PUBLISHED = 0;


    public static function generateRandomString($length = 20, $flag_str = 1)
    {
        if ($flag_str == 0) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $characters = '0123456789';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function str_starts_with($haystack, $needle)
    {
        return substr_compare($haystack, $needle, 0, strlen($needle))
            === 0;
    }
    public static function str_ends_with($haystack, $needle)
    {
        return substr_compare($haystack, $needle, -strlen($needle))
            === 0;
    }

    public static function GenerateSerial($code, $prefix, $append)
    {
        $serial = CommonHelper::generateRandomString($prefix) . $code . CommonHelper::generateRandomString($append);
        return $serial;
    }

    public static function getRoleName($user_id)
    {
        $roles = Yii::$app->authManager->getRolesByUser($user_id);
        if (!$roles) {
            return null;
        }

        reset($roles);
        /* @var $role \yii\rbac\Role */
        $role = current($roles);

        return $role->name;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PUBLISHED => Yii::t('app', 'Published'),
            self::STATUS_NOT_PUBLISHED => Yii::t('app', 'Not Published'),
        ];
    }


    public static function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function generateSlug($name)
    {
        $string = trim($name);

        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace('/[^\x{0600}-\x{06FF}a-z0-9 -]/u','', $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", "-", $string);
        // //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        //remove the start & end dashes
        $string = trim($string, "-");
        $string = rtrim($string,"-");
        return $string;
    }


}
