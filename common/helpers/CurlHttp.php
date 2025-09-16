<?php
namespace common\helpers;

require '../../vendor/autoload.php'; // Include Composer's autoloader

use Curl\Curl;

class CurlHttp
{
    public $base_url;
    public $curl;

    public static function instance()
    {
        return new self();
    }

    public function __construct()
    {
        $this->base_url = $base_url;

        $this->oursms_username = env("OURSMS_USERNAME");
        $this->oursms_token = env("OURSMS_TOKEN");
        $this->oursms_src = env("OURSMS_SRC");
        $this->oursms_pass = env("OURSMS_PASS");

        $this->curl = new Curl();
    }


    public function makePaymentRequest($data)
    {                
        $serverKey = '';
        $url = 'https://secure.clickpay.com.sa/payment/request';

        $curl = new Curl();

        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('authorization', $serverKey);

        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_FAILONERROR, FALSE);
        
        $json_data = json_encode($data);        
        $curl->post($url, $json_data);        
    
        if ($curl->error) {
            return $curl->error_code . ': ' . $curl->error_message;
        } else {
            return json_decode($curl->response, true);
        }    
    }

    public function getPaymentStatus($data)
    {                
        $serverKey = '';
        $url = 'https://secure.clickpay.com.sa/payment/query';

        $curl = new Curl();

        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('authorization', $serverKey);

        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_FAILONERROR, FALSE);
        
        $json_data = json_encode($data);        
        $curl->post($url, $json_data);        
    
        if ($curl->error) {
            return $curl->error_code . ': ' . $curl->error_message;
        } else {
            return json_decode($curl->response, true);
        }    
    }

    public function makeRefund($data)
    {                
        $serverKey = '';
        $url = 'https://secure.clickpay.com.sa/payment/request';

        $curl = new Curl();

        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('authorization', $serverKey);

        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_FAILONERROR, FALSE);
        
        $json_data = json_encode($data);        
        $curl->post($url, $json_data);        
    
        if ($curl->error) {
            return $curl->error_code . ': ' . $curl->error_message;
        } else {
            return json_decode($curl->response, true);
        }    
    }

    public function sendSMS($phone, $message)
    {                 
        $url = 'https://api.oursms.com/api-a/msgs';

        $curl = new Curl();                        

        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_FAILONERROR, FALSE);
        $curl->setOpt(CURLOPT_HEADER, FALSE);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
                  
        $curl->post($url,[
            'username' => $this->oursms_username,
            'token' => $this->oursms_token,
            'src' => $this->oursms_src,
            'body' => $message,
            'dests' => '966582500082',
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

        // if ($curl->error) {
        //     return $curl->error_code . ': ' . $curl->error_message;
        // } else {            
        //     return $curl->response;
        // }    
    }

    public function invoiceStatuse($url, $data, $serverKey)
    {
        $url = 'https://secure.clickpay.com.sa/payment/invoice/status';

        $curl = new Curl();

        $curl->setHeader('Content-Type', 'application/json');

        $curl->setHeader('Authorization', 'Bearer ' . $serverKey);

        $json_data = json_encode($data);

        $curl->post($url, $json_data);

        if ($curl->error) {
            return 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
        } else {
            return 'Response: ' . $curl->response;
        }

        //$invoice_data = array(
        //"profile_id" => "{{profile_id}}",
        //"invoice_id" => "22330"
        //);
    }


    public function __destruct()
    {
        $this->curl->close();
    }
}

// Example usage of the CurlHttp class:

// $CurlHttp = new CurlHttp('https://api.example.com');
//
// // GET request
// $getResponse = $CurlHttp->get('/resource');
//
// // POST request
// $postData = ['param1' => 'value1', 'param2' => 'value2'];
// $postResponse = $CurlHttp->post('/resource', $postData);
//
// // PUT request
// $putData = ['param1' => 'new_value'];
// $putResponse = $CurlHttp->put('/resource', $putData);
//
// // Process responses as needed
// echo $getResponse . PHP_EOL;
// echo $postResponse . PHP_EOL;
// echo $putResponse . PHP_EOL;


