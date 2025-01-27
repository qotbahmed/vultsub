<?php
namespace common\helpers;
use common\helpers\CurlHttp;

use Curl\Curl;

class SMSHelper
{
    public $message;    
    public $phone;

    public static function instance()
    {
        return new self();
    }



    /************************************* Verification ************************************/

    public function sendVerify($number, $otp)
    {
        //check environment
        // if (YII_ENV_DEV) {
        //     return true;
        // }
        
        
        $this->phone =  $number;
        $this->message = \Yii::t('frondend', 'Your otp code is ').$otp;

        $url = 'https://api.oursms.com/api-a/msgs';

        $curl = new Curl();                        

        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_FAILONERROR, FALSE);
        $curl->setOpt(CURLOPT_HEADER, FALSE);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
                  
        $curl->post($url,[
            // 'username' => env("OURSMS_USERNAME"),
            // 'token' => env("OURSMS_TOKEN"),
            // 'src' => env("OURSMS_SRC"),
            'username' => 'Ibrahim@rtelo.com',
            'token' => 'Wh277AktK6ke8ZchoEuj',
            'src' => 'OurSms',
            'body' => $this->message,
            'dests' => $this->phone,
            'priority'=> 0,
            'delay'=> 0,
            'validity'=> 0,
            'maxParts'=> 0,
            'dlr'=> 0,
            'prevDups'=> 0
        ]);  

        
        $result  = json_decode($curl->response);
        
        if($result->accepted == 1){            
            return true;
        }else{
            return false;
        }
    }




    // public static function Send()
    // {
    //     //check environment
    //     // if (YII_ENV_DEV) {
    //     //     return true;
    //     // }

    //     // return CurlHttp::instance()->sendSMS($this->phone, $this->message);

    //     var_dump($this->message);die();

    //     $url = 'https://api.oursms.com/api-a/msgs';

    //     $curl = new Curl();                        

    //     $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
    //     $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
    //     $curl->setOpt(CURLOPT_FAILONERROR, FALSE);
    //     $curl->setOpt(CURLOPT_HEADER, FALSE);
    //     $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
    //     $curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
                  
    //     $curl->post($url,[
    //         'username' => env("OURSMS_USERNAME"),
    //         'token' => env("OURSMS_TOKEN"),
    //         'src' => env("OURSMS_SRC"),
    //         'body' => $this->message,
    //         'dests' => $this->phone,
    //         'priority'=> 0,
    //         'delay'=> 0,
    //         'validity'=> 0,
    //         'maxParts'=> 0,
    //         'dlr'=> 0,
    //         'prevDups'=> 0
    //     ]);  
        
    //     $result  = json_decode($curl->response);
        
    //     if($result->accepted == 1){            
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    
}
