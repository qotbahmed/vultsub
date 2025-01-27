<?php

namespace common\helpers;

use api\resources\CustomerRequestResource;
use backend\models\Settings;
use common\models\CustomerRequest;
use common\models\Payment;
use common\models\RequestsTrackingLog;
use common\models\Notifications;
use common\models\RequestLog;
use common\models\User;
use api\helpers\ResponseHelper;
use common\helpers\NotificationHelper;
use common\models\UserProfile;

use common\helpers\CurlHttp;
use DateTime;

class SessionsHelper
{
    //public $notifyWeb;
    public static function instance()
    {
        return new self();
    }

    public function __construct()
    {
        $this->from = \Yii::$app->user->id;
    }

    public function startSession($requesObj, $type)
    {
        $requestObject = CustomerRequest::findOne($requesObj->id);

        if(date("Y-m-d H:i") < ($requestObject->session_date.' '.$requestObject->session_time))
        {
            if($type == "dash")                
            return false;
        }

        //validate incoming data @ToBeDone
        $requestObject->request_tracking_status = CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION;
        
        if(!$requestObject->save()){
            var_dump($requestObject->errors);
        }
        

        $add = new RequestsTrackingLog();
        $add->request_id = $requestObject->id;
        $add->status = CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION;
        
        if (!$add->save()) {
            var_dump($add->errors);
        }

        NotificationHelper::instance()->UpdateTrackingStatus($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION);        
        return true;
    }

    public function endSession($requesObj, $type)
    {
        $requestObject = CustomerRequest::findOne($requesObj->id);

        if( date("Y-m-d H:i") < ($requestObject->session_date.' '.$requestObject->session_time)){
            if($type == "dash")                
            return false;
        }

        //validate incoming data @ToBeDone
        $requestObject->request_tracking_status = CustomerRequest::REQUEST_TRACKING_STATUS_ENDED;
        if(!$requestObject->save()){
            var_dump($requestObject->errors);
        }

        $add = new RequestsTrackingLog();
        $add->request_id = $requestObject->id;
        $add->status = CustomerRequest::REQUEST_TRACKING_STATUS_ENDED;
        
        if( !$add->save()){
            var_dump($add->errors);
        }
        
        NotificationHelper::instance()->UpdateTrackingStatus($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_ENDED);
        return true;
    }

    public function customerCancelSession($requesObj, $reason = null)
    {
        $settings = Settings::findOne(1);

        $requestObject = CustomerRequest::findOne($requesObj->id);

        $checkPayment = Payment::find()
        ->where(['request_id' => $requesObj->id,'user_id' => $requesObj->user_id])   
        ->andWhere(['!=', 'refunded', Payment::STATUS_REFUNDED])     
        ->one();

        if($checkPayment)
        {            
            $sessionDateTime = new DateTime($requesObj->session_date . ' ' . $requesObj->session_time);
            $currentDateTime = new DateTime();

            // Calculate the time difference in hours
            $hourDifference = $currentDateTime->diff($sessionDateTime)->h;

            if ($hourDifference <= $refundPolicyHours) {
                $amount = $requesObj->paid_amount / 2;
                $type = CustomerRequest::PARTIAL_REFUND;
            } else {
                $amount = $requesObj->paid_amount;
                $type = CustomerRequest::FULL_REFUND;
            }         

            $data = array(
                "profile_id" => 43503,
                "tran_type" => "refund",
                "tran_class" => "ecom",
                "cart_id" => "cart_".$requesObj->id,
                "cart_currency" => "SAR",
                "cart_amount" => $amount,
                "cart_description" => ($reason)?: "لا يوجد",
                "tran_ref" => $checkPayment->tran_ref
            );
            
            $result = CurlHttp::instance()->makeRefund($data);

            // var_dump($data);die();

            if ($settings->service_fee_type == Settings::SERVICE_FEE_TYPE_FIXED) {
                $fees = $settings->service_fees;
            } else {
                $fees = $amount * $settings->service_fees / 100;
            }

            if(is_array($result) && $result['payment_result']['response_status'] != "A")
                return false;

            if($type == CustomerRequest::PARTIAL_REFUND){
                $nanny = User::findOne($requesObj->nany_id);

                $nanny->wallet += $amount - $fees;
                $nanny->wallet_last_update = strtotime(date("Y-m-d H:i"));
                
                if (!$nanny->save()){
                    var_dump($nanny->errors);                
                }
            }

            // Save new nany profit to invoice details
            $checkPayment->refunded = Payment::STATUS_REFUNDED;
            $checkPayment->refunded_to = ($type == CustomerRequest::PARTIAL_REFUND) ? Payment::REFUNDED_TO_NANNY_CUSTOMER : Payment::REFUNDED_TO_CUSTOMER;                        
            $checkPayment->nany_profit = ($type == CustomerRequest::PARTIAL_REFUND) ? $amount - $fees : 0;
            $checkPayment->refund_type = ($type == CustomerRequest::PARTIAL_REFUND) ? Payment::PARTIAL_REFUND : Payment::FULL_REFUND;
            $checkPayment->save();

            // save refunded amount to customer request details
            $requestObject->refund_to = ($type == CustomerRequest::PARTIAL_REFUND) ? Payment::REFUNDED_TO_NANNY_CUSTOMER : Payment::REFUNDED_TO_CUSTOMER;                        
            $requestObject->refunded_amount = ($type == CustomerRequest::PARTIAL_REFUND) ? $amount - $fees : 0;
            $requestObject->customer_refunded_amount = ($type == CustomerRequest::PARTIAL_REFUND) ? $amount : 0;

            if(!$requestObject->save()){
                var_dump($requestObject->errors);
            }

            // var_dump($type.' '.($amount - $fees).' '.$requestObject->refunded_amount.' '.$settings->service_fee_type);die();
        }

        //validate incoming data @ToBeDone
        $requestObject->request_tracking_status = CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL;                

        if(!$requestObject->save()){
            var_dump($requestObject->errors);
        }

        $add = new RequestsTrackingLog();
        $add->request_id = $requestObject->id;
        $add->cancel_reason = $reason;
        $add->status = CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL;
        
        if( !$add->save()){
            var_dump($add->errors);
        }        



        // Hide extended transactions from wallet
        Payment::updateAll(['hide' => 1], ['AND', 
            'extended = 1',             
            ['=', 'request_id', $requestObject->id]
        ]);

                    
        NotificationHelper::instance()->UpdateTrackingStatus($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL);
        
        if($checkPayment && $type == CustomerRequest::FULL_REFUND){
            NotificationHelper::instance()->customerFullRefund($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL);
        }elseif($checkPayment && $type == CustomerRequest::PARTIAL_REFUND){
            NotificationHelper::instance()->customerNannyHalfRefund($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL);
        }

        return true;
    }

    public function adminCancelSession($requesObj,$reason, $action,$status)
    {
        $settings = Settings::findOne(1);

        $requestObject = CustomerRequest::findOne($requesObj->id);

        $checkPayment = Payment::find()
        ->where(['request_id' => $requesObj->id,'user_id' => $requesObj->user_id])
        ->andWhere(['!=', 'refunded', Payment::STATUS_REFUNDED])
        ->one();

        if($checkPayment)
        {   
            if ($action == CustomerRequest::PARTIAL_REFUND) {
                // partial refund
                $amount = $requesObj->paid_amount / 2;
                $type = CustomerRequest::PARTIAL_REFUND;
            }else {
                // full refund
                $amount = $requesObj->paid_amount;
                $type = CustomerRequest::FULL_REFUND;
            }

            $data = array(
                "profile_id" => 43503,
                "tran_type" => "refund",
                "tran_class" => "ecom",
                "cart_id" => "cart_".$requesObj->id,
                "cart_currency" => "SAR",
                "cart_amount" => $amount,
                "cart_description" => ($reason)?: "لا يوجد",
                "tran_ref" => $checkPayment->tran_ref
            );
            
            $result = CurlHttp::instance()->makeRefund($data);

            // var_dump($result);die();

            if($settings->service_fee_type == Settings::SERVICE_FEE_TYPE_FIXED){
                $fees = $settings->service_fees;
            }else{
                $fees = $amount * $settings->service_fees / 100;
            }

            if(is_array($result) && $result['payment_result']['response_status'] != "A")
                return false;

            if($type == CustomerRequest::PARTIAL_REFUND){
                $nanny = User::findOne($requesObj->nany_id);

                $nanny->wallet += $amount - $fees;
                $nanny->wallet_last_update = strtotime(date("Y-m-d H:i"));
                
                if (!$nanny->save()){
                    var_dump($nanny->errors);                
                }
            }

            // Save new nany profit to invoice details
            $checkPayment->refunded = Payment::STATUS_REFUNDED;
            $checkPayment->refunded_to = ($type == CustomerRequest::PARTIAL_REFUND) ? Payment::REFUNDED_TO_NANNY_CUSTOMER : Payment::REFUNDED_TO_CUSTOMER;
            $checkPayment->nany_profit = ($type == CustomerRequest::PARTIAL_REFUND) ? $amount - $fees : 0;
            $checkPayment->refund_type = ($type == CustomerRequest::PARTIAL_REFUND) ? Payment::PARTIAL_REFUND : Payment::FULL_REFUND;
            $checkPayment->save();

            // save refunded amount to customer request details
            $requestObject->refund_to = ($type == CustomerRequest::PARTIAL_REFUND) ? Payment::REFUNDED_TO_NANNY_CUSTOMER : Payment::REFUNDED_TO_CUSTOMER;                        
            $requestObject->refunded_amount = ($type == CustomerRequest::PARTIAL_REFUND) ? $amount - $fees : 0;
            $requestObject->customer_refunded_amount = ($type == CustomerRequest::PARTIAL_REFUND) ? $amount : 0;
            $requestObject->save();
        }

        //validate incoming data @ToBeDone
        $requestObject->request_tracking_status = CustomerRequest::REQUEST_TRACKING_STATUS_ADMIN_CANCEL;                

        if(!$requestObject->save()){
            var_dump($requestObject->errors);
        }

        $add = new RequestsTrackingLog();
        $add->request_id = $requestObject->id;
        $add->cancel_reason = $reason;
        $add->status = CustomerRequest::REQUEST_TRACKING_STATUS_ADMIN_CANCEL;
        
        if( !$add->save()){
            var_dump($add->errors);
        }        

        // Hide extended transactions from wallet
        Payment::updateAll(['hide' => 1], ['AND', 
            'extended = 1',             
            ['=', 'request_id', $requestObject->id]
        ]);
                    
         NotificationHelper::instance()->UpdateTrackingStatus($requestObject->id, $status);

        if($action == CustomerRequest::FULL_REFUND){
            NotificationHelper::instance()->customerFullRefund($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_ADMIN_CANCEL);
        }else{
            NotificationHelper::instance()->customerNannyHalfRefund($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_ADMIN_CANCEL);
        }        

        return true;
    }

    public function nanyCancelSession($requesObj, $reason = null)
    {
        $requestObject = CustomerRequest::findOne($requesObj->id);

        $checkPayment = Payment::find()
        ->where(['request_id' => $requesObj->id,'nany_id' => $requesObj->nany_id])        
        ->one();        

        if($checkPayment)
        {                       
            $data = array(
                "profile_id" => 43503,
                "tran_type" => "refund",
                "tran_class" => "ecom",
                "cart_id" => "cart_".$requesObj->id,
                "cart_currency" => "SAR",
                "cart_amount" => $requesObj->paid_amount,
                "cart_description" => ($reason)?: "لا يوجد",
                "tran_ref" => $checkPayment->tran_ref
            );
            
            $result = CurlHttp::instance()->makeRefund($data);

            // var_dump($result['payment_result']['response_status']);die();

            if(is_array($result) && $result['payment_result']['response_status'] != "A")
                return false;
            
            // save refunded amount to customer request details
            $checkPayment->refunded = Payment::STATUS_REFUNDED;
            $checkPayment->refunded_to = Payment::REFUNDED_TO_CUSTOMER;
            $checkPayment->refund_type = Payment::FULL_REFUND;
            $checkPayment->save();

            //
            $requestObject->refund_to = Payment::REFUNDED_TO_CUSTOMER;                        
            $requestObject->refunded_amount = 0;
            $requestObject->customer_refunded_amount = 0;
            $requestObject->save();
        }

        //validate incoming data @ToBeDone
        $requestObject->request_tracking_status = CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL;
        if(!$requestObject->save()){
            var_dump($requestObject->errors);
        }

        $add = new RequestsTrackingLog();
        $add->request_id = $requestObject->id;
        $add->cancel_reason = $reason;
        $add->status = CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL;

        if( !$add->save()){
            var_dump($add->errors);
        }

        // Hide extended transactions from wallet
        Payment::updateAll(['hide' => 1], ['AND', 
            'extended = 1',             
            ['=', 'request_id', $requestObject->id]
        ]);

        NotificationHelper::instance()->UpdateTrackingStatus($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL);

        if(isset($checkPayment)){
            NotificationHelper::instance()->customerFullRefund($requestObject->id, CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL);
        }

        return true;
    }

    public function refundNanny($id, $paid_amount, $request_id)
    {
        $settings = Settings::findOne(1);

        $user = User::findOne($id);

        if($settings->service_fee_type == Settings::SERVICE_FEE_TYPE_FIXED){
            $fees = $settings->service_fees;
        }else{
            $fees = $paid_amount * $settings->service_fees / 100;
        }

        $user->wallet += $paid_amount - $fees;
        $user->wallet_last_update = strtotime(date("Y-m-d H:i"));
        
        if (!$user->save()){
           var_dump($user->errors);
           return false;
        }

        $paymentLog = Payment::find()
        ->where(['request_id' => $request_id, 'nany_id' => $user->id, 'extended' => Payment::PAYMENT_NOT_EXTENDED])        
        ->one();

        $paymentLog->refunded = Payment::STATUS_REFUNDED;
        $paymentLog->refunded_to = Payment::REFUNDED_TO_NANNY;
        $paymentLog->save();

        return true;
    }
    public function refundCustomer($id,$paid_amount, $request_id)
    {
        $user = User::findOne($id);

        $paymentLog = Payment::find()
        ->where(['request_id' => $request_id, 'user_id' => $user->id, 'extended' => Payment::PAYMENT_NOT_EXTENDED])        
        ->one();

        

        $paymentLog->refunded = Payment::STATUS_REFUNDED;
        $paymentLog->refunded_to = Payment::REFUNDED_TO_NANNY;
        $paymentLog->save();

        return true;
    }

    public function checkPreferredAge($specifiedNany,$request)
    {
        $specifiedNany= User::findOne($specifiedNany->id);
        $request= CustomerRequest::findOne($request->id);
        
        $relatives = $request->allRelatives;
        $nannyFromAgeInMonth = 0;
        $nannyToAgeInMonth = 0;

        if ($specifiedNany->userProfile->preferred_age_from_unit == UserProfile::AGE_UNIT_MONTH) {
            $nannyFromAgeInMonth = $specifiedNany->userProfile->preferred_age_from ;
        } elseif ($specifiedNany->userProfile->preferred_age_from_unit == UserProfile::AGE_UNIT_YEAR) {
            $nannyFromAgeInMonth = $specifiedNany->userProfile->preferred_age_from  * 12;
        }

        if ($specifiedNany->userProfile->preferred_age_to_unit == UserProfile::AGE_UNIT_MONTH) {
            $nannyToAgeInMonth =  $specifiedNany->userProfile->preferred_age_to ;
        } elseif ($specifiedNany->userProfile->preferred_age_to_unit == UserProfile::AGE_UNIT_YEAR) {
            $nannyToAgeInMonth = $specifiedNany->userProfile->preferred_age_to * 12;
        }


        foreach ($relatives as $relative){

            $ageInMonth=$relative->age; // as month
            if (!($ageInMonth>=$nannyFromAgeInMonth && $ageInMonth<=$nannyToAgeInMonth)){
                return true;
            }
        }


        return false;

    }

}