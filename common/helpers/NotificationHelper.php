<?php

namespace common\helpers;

use api\resources\CustomerRequestResource;
use backend\models\Settings;
use common\models\CustomerRequest;
use common\models\Notifications;
use common\models\ExtendedRequests;
use common\models\RequestLog;
use common\models\RequestsTrackingLog;
use common\models\User;
use common\models\Rate;
use common\models\Message;
use Yii;

class NotificationHelper
{
    public $notifyTopic;
    public $topic;
    public $notifyToken;
    public $data;
    public $deviceToken;

    public $key_id;
    public $from;
    public $to;
    public $module;
    public $module_id;
    public $seen;
    public $title_ar;
    public $title_en;
    public $message_ar;
    public $message_en;
    public $payload;
    public $request_id;

    public $route;

    //public $notifyWeb;

    public static function instance()
    {
        return new self();
    }

    public function __construct()
    {
        if (!Yii::$app instanceof \yii\console\Application) {
            $this->from = Yii::$app->user->id;
        } else {

        }
    }



    public function MinutesRemainingNanny($id)
    {
        $settings = Settings::findOne(1);
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        $requestLog = RequestLog::find()->where(['request_id' => $id, 'nany_id' => $requestObj->nany_id])->one();


        $this->notifyToken = true;
        $this->key_id = Notifications::TYPE_UPDATE_REQUEST_TRACKING_STATUS;
        $this->module = 'request';
        $this->module_id = $requestLog->id;
        if (!$requestObj) return false;

//        //// send to customer
        $this->deviceToken = $requestObj->nanny->firebase_token;
        $this->title_ar = "دقائق متبقية و تنتهي الجلسة";
        $this->title_en = "Minutes Remain and the session will be ended";
        $this->message_ar = "متبقي علي انتهاء الجلسة " . $settings->end_session_alarm . ' دقيقة';
        $this->message_en = $settings->end_session_alarm . ' Minutes Remaining' . " to end the session";
        $this->to = $requestObj->nany_id;
        $this->route = 'sessionDetailsScreen';
        // $this->payload = json_encode(['id' => $requestObj->request_tracking_status, 'text' => $requestObj->RequestStatusTrackingList()[$requestObj->status], 'request_id' => $requestLog->id]);

        $this->payload = json_encode(['id' => Notifications::REQUEST_WILL_END, 'text' => $requestObj->RequestStatusTrackingList()[$requestObj->status], 'request_id' => $requestLog->id]);
        
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route];
        $this->Send();



    }
public function MinutesRemaining($id)
    {
        $settings = Settings::findOne(1);
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();


        $this->notifyToken = true;
        $this->key_id = Notifications::TYPE_UPDATE_REQUEST_TRACKING_STATUS;
        $this->module = 'request';
        $this->module_id = $id;
        if (!$requestObj) return false;

//        //// send to customer


        $this->deviceToken = $requestObj->user->firebase_token;
        $this->title_ar = "دقائق متبقية و تنتهي الجلسة";
        $this->title_en = "Minutes Remain and the session will be ended";
        $this->message_ar = "متبقي علي انتهاء الجلسة " . $settings->end_session_alarm . ' دقيقة';
        $this->message_en = $settings->end_session_alarm . ' Minutes Remaining' . " to end the session";
        $this->to = $requestObj->user_id;
        $this->route = 'orderDetails';
        $this->payload = json_encode(['id' =>Notifications::REQUEST_WILL_END, 'text' => $requestObj->RequestStatusTrackingList()[$requestObj->status]]);
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route];
        $this->Send();


    }

    public function Send()
    {
        // save
        $this->saveNotification();
        //send to firebase db
//        if ($this->notifyWeb) {
//            $this->notifyFirebaseDb();
//        }

//        //send to mobile apps
        if ($this->notifyToken) {
            $this->notifyMobileToken();
        }


        /*
        if ($this->notifyTopic) {
            $this->notifyMobileTopic();
        }*/
        return true;
    }

    private function saveNotification()
    {
        $Notification = new Notifications();
        $Notification->key_id = $this->key_id;
        $Notification->from_id = $this->from;
        $Notification->to_id = $this->to;
        $Notification->topic = $this->topic;
        $Notification->message_ar = $this->message_ar;
        $Notification->message_en = $this->message_en;
        $Notification->module = $this->module;
        $Notification->module_id = $this->module_id;
        $Notification->seen = $this->seen;
        $Notification->title_ar = $this->title_ar;
        $Notification->title_en = $this->title_en;
        $Notification->payload = $this->payload;
        $Notification->route = $this->route;
        $Notification->request_id = $this->request_id;
        $Notification->created_at = date('Y-m-d H:i:s');

        if (!$Notification->save()) {
            // var_dump($Notification->errors);
            die;
        }
    }

    public function callNotification($name,$avatar,$req_id,string $rtc_channel, $rtc_token, string $appID, \api\resources\UserResource $other)
    {

        $this->notifyToken = true;
        $this->deviceToken = $other->firebase_token;
        $this->key_id = Notifications::TYPE_CALL;
        $this->module = 'call';
        $this->module_id = $req_id;
        $this->title_ar = "مكالمة";
        $this->title_en = "New Call";
        $this->message_ar = " تم إستلام مكالمة جديدة  ";
        $this->message_en = "New Call received";
        $this->route = 'call';
        $this->to = $other->id;
        $this->data = ['key' => (string)$this->key_id,'route'=>$this->route, 'type' =>$this->module, 'id' => (string)$this->module_id, 'rtc_channel' => (string)$rtc_channel, 'rtc_token' => (string)$rtc_token, 'appID' => (string)$appID,'name'=>(string)$name,'avatar'=>(string)$avatar];
        $this->payload = json_encode(['key' => $this->key_id, 'route'=>$this->route,'type' => $this->module, 'id' => $this->module_id, 'rtc_channel' => $rtc_channel, 'rtc_token' => $rtc_token, 'appID' => $appID,'name'=>$name,'avatar'=>$avatar]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    private function notifyMobileTopic()
    {
        $result = \Yii::$app->fcm
            ->createRequest()
            ->setTarget(\aksafan\fcm\source\builders\apiV1\MessageOptionsBuilder::TOPIC, $this->topic)
            ->setData($this->data)
            ->setNotification($this->title_ar, $this->message_ar)
            ->send();
        return true;
    }

    private function notifyMobileToken()
    {        
        $reciever = User::find()->where(['id' => $this->to])->one();

        $deviceToken = $this->deviceToken;
        $result = \Yii::$app->fcm
            ->createRequest()
            ->setTarget(\aksafan\fcm\source\builders\apiV1\MessageOptionsBuilder::TOKEN, $deviceToken)
            ->setData($this->data)
            ->setNotification($this->title_ar, $this->message_ar)
            ->setAndroidConfig([
                'ttl' => '3600s',
                'priority' => 'high',
                'data' => [
                    'click_action'=>'FLUTTER_NOTIFICATION_CLICK',
                ],
                'notification' => [
                    'title' => ($reciever &&  $reciever->userProfile->locale == 'ar') ? $this->title_ar : $this->title_en,
                    'body' =>  ($reciever &&  $reciever->userProfile->locale == 'ar') ? $this->message_ar : $this->message_en,
                    //'icon' => 'stock_ticker_update',
                    //'color' => '#ff0000',
                ],
            ])
            //            ->setApnsConfig([
            //                'headers' => [
            //                    'apns-priority' => '10',
            //                ],
            //                'payload' => [
            //                    'aps' => [
            //                        'alert' => [
            //                            'title' => 'iOS Title',
            //                            'body' => 'iOS Description.',
            //                        ],
            //                        'badge' => 42,
            //                    ],
            //                ],
            //            ])
            ->send();

        return true;
    }

    private function notifyFirebaseDb()
    {
        \Yii::$app->firebase->insert([
            $this->to => [
                'title' => $this->title,
                'body' => $this->message,
                'seen' => 0,
                'type' => $this->type,
            ],
        ]);
    }

    /* ***************************Helpers******************************* */
    public function NewPublicRequest($id)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $this->notifyTopic = true;
        $this->key_id = Notifications::TYPE_NEW_PUBLIC_REQUEST;
        $this->module = 'request';
        $this->module_id = $id;
        $this->title_ar = "طلب جديد";
        $this->title_en = "New Request";
        $this->message_ar = " تم إستلام طلب من  " . $requestObj->request->user->userProfile->fullName;
        $this->message_en = "Request received from " . $requestObj->request->user->userProfile->fullName;
        //topics [nanny , nurse , orderly]
        $this->topic = CustomerRequest::getTopicsList()[$requestObj->needed_role];
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)CustomerRequest::STATUS_NEW ];
        $this->payload = json_encode(['id' => CustomerRequest::STATUS_NEW, 'text' => $requestObj->statuses()[CustomerRequest::STATUS_NEW] ]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function NewPrivateRequest($id)
    {
        $requestObj = RequestLog::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nany->firebase_token;
        $this->key_id = Notifications::TYPE_NEW_PRIVATE_REQUEST;
        $this->module = 'request';
        $this->module_id = $requestObj->id;
        $this->title_ar = "طلب جديد";
        $this->title_en = "New Request";
        $this->message_ar = "تم إستلام طلب من " . $requestObj->request->user->userProfile->fullName;
        $this->message_en = "Request received from " . $requestObj->request->user->userProfile->fullName;
        $this->to = $requestObj->nany_id;
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)CustomerRequest::STATUS_NEW];
        $this->payload = json_encode(['id' => CustomerRequest::STATUS_NEW, 'text' => $requestObj->request->statuses()[CustomerRequest::STATUS_NEW]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }


    public function NewRequestOffer($id)
    {
        $requestObj = RequestLog::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->request->user->firebase_token;
        $this->key_id = Notifications::TYPE_NEW_REQUEST_OFFER;
        $this->module = 'request';
        $this->module_id = $requestObj->request->id;
        $this->title_ar = "عرض جديد";
        $this->title_en = "New Offer";
        $this->message_ar = "تم إستلام عرض من " . $requestObj->nany->userProfile->fullName;
        $this->message_en = "Offer received from " . $requestObj->nany->userProfile->fullName;
        $this->to = $requestObj->request->user_id;
        $this->route = 'offersListScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$requestObj->request->id, 'route' => $this->route, 'request_status' => (string)CustomerRequest::STATUS_OFFERED];
        $this->payload = json_encode(['id' => CustomerRequest::STATUS_OFFERED, 'text' => $requestObj->request->statuses()[CustomerRequest::STATUS_OFFERED]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }


    public function NewPublicRequestToAll($id)
    {
        $requestObj = RequestLog::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nany->firebase_token;
        $this->key_id = Notifications::TYPE_NEW_PUBLIC_REQUEST;
        $this->module = 'request';
        $this->module_id = $requestObj->id;
        $this->title_ar = "طلب جديد";
        $this->title_en = "New Request";
        $this->message_ar = " تم إستلام طلب من  " . $requestObj->request->user->userProfile->fullName;
        $this->message_en = "Request received from " . $requestObj->request->user->userProfile->fullName;
        $this->to = $requestObj->nany_id;
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'request_status' => (string)CustomerRequest::STATUS_NEW];
        $this->payload = json_encode(['id' => CustomerRequest::STATUS_NEW, 'text' => $requestObj->request->statuses()[CustomerRequest::STATUS_NEW]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function AcceptOffer($id)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        $requestLog = RequestLog::find()->where(['request_id' => $id, 'nany_id' => $requestObj->nany_id])->one();
        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nanny->firebase_token;
        $this->key_id = Notifications::TYPE_ACCEPT_NANNY_OFFER;
        $this->module = 'request';
        $this->module_id = $requestLog->id;
        $this->title_ar = "تم قبول العرض";
        $this->title_en = "Offer accepted";
        $this->message_ar = " تم قبول العرض بواسطة  " . $requestObj->user->userProfile->fullName;
        $this->message_en = "Offer accepted by " . $requestObj->user->userProfile->fullName;
        $this->to = $requestObj->nany_id;
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$requestLog->id, 'route' => $this->route];
        $this->payload = json_encode(['id' => CustomerRequest::STATUS_ACCEPTED_BY_CUSTOMER, 'text' => $requestObj->statuses()[CustomerRequest::STATUS_ACCEPTED_BY_CUSTOMER]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function paySession($id)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $requestLog = RequestLog::find()->where(['request_id' => $id, 'nany_id' => $requestObj->nany_id])->one();

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nanny->firebase_token;
        $this->key_id = Notifications::TYPE_SESSION_PAID;
        $this->module = 'request';
        $this->module_id = $requestLog->id;
        $this->title_ar = "تم دفع الجلسة";
        $this->title_en = "Session paid";
        $this->message_ar = " تم دفع قيمه الجلسة بواسطة  " . $requestObj->user->userProfile->fullName;
        $this->message_en = "Session paid by " . $requestObj->user->userProfile->fullName;
        $this->to = $requestObj->nany_id;
        // $this->route = 'wallet';
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' =>  (string)CustomerRequest::PAYMENT_STATUS_COMPLETED];
        $this->payload = json_encode(['id' => CustomerRequest::PAYMENT_STATUS_COMPLETED, 'text' => $requestObj->getPaymentStatus()[CustomerRequest::PAYMENT_STATUS_COMPLETED]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }


    public function payExtendSession($id)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $requestLog = RequestLog::find()->where(['request_id' => $id, 'nany_id' => $requestObj->nany_id])->one();

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nanny->firebase_token;
        $this->key_id = Notifications::TYPE_SESSION_PAID;
        $this->module = 'request';
        $this->module_id = $requestLog->id;
        $this->title_ar = "تم دفع تمديد الجلسة";
        $this->title_en = "Extend session paid";
        $this->message_ar = " تم دفع قيمه تمديد الجلسة بواسطة  " . $requestObj->user->userProfile->fullName;
        $this->message_en = "Extended session paid by " . $requestObj->user->userProfile->fullName;
        $this->to = $requestObj->nany_id;
        // $this->route = 'wallet';
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' =>  (string)CustomerRequest::PAYMENT_STATUS_COMPLETED];
        $this->payload = json_encode(['id' => CustomerRequest::PAYMENT_STATUS_COMPLETED, 'text' => $requestObj->getPaymentStatus()[CustomerRequest::PAYMENT_STATUS_COMPLETED]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    // 2 accepted by nanny, 3 rejected by nanny, 4 ended by customer, 5 ended by nanny, 6 cancelled by customer, 7 accepted by customer
    public function UpdateRequestStatus($id, $status)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $requestLog = RequestLog::find()->where(['request_id' => $id, 'nany_id' => $requestObj->nany_id])->one();

        $this->notifyToken = true;
        $this->key_id = Notifications::TYPE_UPDATE_REQUEST_STATUS;
        $this->module = 'request';        

        if ($status == CustomerRequest::STATUS_ACCEPTED_BY_NANNY) {
            $this->module_id = $id;
            $this->deviceToken = $requestObj->user->firebase_token;
            $this->title_ar = "تم قبول طلبك";
            $this->title_en = "Request accepted";
            $this->message_ar = "تم قبول الطلب بواسطة " . $requestObj->nanny->userProfile->fullName;
            $this->message_en = "Your request accepted by " . $requestObj->nanny->userProfile->fullName;
            $this->to = $requestObj->user_id;
            $this->route = 'orderDetails';
            $this->payload = json_encode(['id' => CustomerRequest::STATUS_ACCEPTED_BY_NANNY, 'text' => $requestObj->statuses()[$status]]);
        } elseif ($status == CustomerRequest::STATUS_REJECTED_BY_NANNY) {
            $this->module_id = $id;
            $this->deviceToken = $requestObj->user->firebase_token;
            $this->title_ar = "تم رفض طلبك";
            $this->title_en = "Request rejected";
            $this->message_ar = "تم رفض الطلب بواسطة " . $requestObj->nanny->userProfile->fullName;
            $this->message_en = "Request rejected by " . $requestObj->nanny->userProfile->fullName;
            $this->to = $requestObj->user_id;
            $this->route = 'orderDetails';
            $this->payload = json_encode(['id' => CustomerRequest::STATUS_REJECTED_BY_NANNY, 'text' => $requestObj->statuses()[$status]]);
        } elseif ($status == CustomerRequest::STATUS_ENDED) {
            $this->module_id = $id;
            $this->deviceToken = $requestObj->user->firebase_token;
            $this->title_ar = "لقد انتهى طلبك!";
            $this->title_en = "Your request has been ended!";
            $this->message_ar = " لقد انهيت الطلب الذى قمت به مع " . $requestObj->nanny->userProfile->fullName;
            $this->message_en = $requestObj->nanny->userProfile->fullName . " rejected your request!";
            $this->to = $requestObj->user_id;
            $this->route = 'orderDetails';
            $this->payload = json_encode(['id' => CustomerRequest::STATUS_ENDED, 'text' => $requestObj->statuses()[$status]]);
        } elseif ($status == CustomerRequest::STATUS_ENDED_BY_NANNY) {
            $this->module_id = $requestLog->id;
            $this->deviceToken = $requestObj->nanny->firebase_token;
            $this->title_ar = "تم تحويل المبلغ اللى محفظتك";
            $this->title_en = "The amount has been transferred to your wallet";
            $this->message_ar = "لقد انهيت الجلسة بنجاح، تم تحويل المبلغ لمحفظتك";
            $this->message_en = "You have completed the session successfully, the amount has been transferred to your wallet";
            $this->to = $requestObj->nany_id;
            $this->route = 'sessionDetailsScreen';
            $this->payload = json_encode(['id' => CustomerRequest::STATUS_ENDED_BY_NANNY, 'text' => $requestObj->statuses()[$status]]);
        } elseif ($status == CustomerRequest::STATUS_CANCELLED_BY_CUSTOMER) {
            $this->module_id = $requestLog->id;
            $this->deviceToken = $requestObj->nanny->firebase_token;
            $this->title_ar = "تم الغاء طلب " . $requestObj->user->userProfile->fullName;
            $this->title_en = $requestObj->user->userProfile->fullName . "`s request has been cancelled";
            $this->message_ar = "قامت " . $requestObj->user->userProfile->fullName . " بإلغاء الطلب، سيظهر لديك في جلساتك واختر الطلبات السابقة";
            $this->message_en = $requestObj->user->userProfile->fullName . " canceled the request, it will appear in your sessions and choose previous requests";
            $this->to = $requestObj->nany_id;
            $this->route = 'sessionDetailsScreen';
            $this->payload = json_encode(['id' => CustomerRequest::STATUS_CANCELLED_BY_CUSTOMER, 'text' => $requestObj->statuses()[$status]]);
        } elseif ($status == CustomerRequest::STATUS_ACCEPTED_BY_CUSTOMER) {
            $this->module_id = $requestLog->id;
            $this->deviceToken = $requestObj->nanny->firebase_token;
            $this->title_ar = "تم قبول العرض الخاص بك  ";
            $this->title_en = "Your offer has been accepted";
            $this->message_ar = "قامت " . $requestObj->user->userProfile->fullName . " بقبول العرض سيظهر لديك في جلساتك.";
            $this->message_en = $requestObj->user->userProfile->fullName . " accepted the offer, it will appear in your sessions.";
            $this->to = $requestObj->nany_id;
            $this->route = 'sessionDetailsScreen';
            $this->payload = json_encode(['id' => CustomerRequest::STATUS_ACCEPTED_BY_CUSTOMER, 'text' => $requestObj->statuses()[$status]]);
        }

        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)$status];
        $this->Send();
    }


    // 0  NOT_STARTED , 1 WAITING_PAYMENT, 2 WAITING_ARRIVAL, 3 IN_SESSION,  4 ENDED
    public function UpdateTrackingStatus($id, $status)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        $requestLog = RequestLog::find()->where(['request_id' => $id, 'nany_id' => $requestObj->nany_id])->one();

        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->key_id = Notifications::TYPE_UPDATE_REQUEST_TRACKING_STATUS;
        $this->module = 'request';        

        if ($status == CustomerRequest::REQUEST_TRACKING_STATUS_WAITING_ARRIVAL) {
            $this->module_id = $id;
            $this->deviceToken = $requestObj->user->firebase_token;
            $this->title_ar = "فى انتظار الوصول";
            $this->title_en = "Waiting arrival";
            $this->message_ar = $requestObj->nanny->userProfile->fullName . "فى الطريق الى موقع الجلسة";
            $this->message_en = $requestObj->nanny->userProfile->fullName . " On the way to session location";
            $this->to = $requestObj->user_id;
            $this->route = 'orderDetails';
            $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_WAITING_ARRIVAL, 'text' => $requestObj->RequestStatusTrackingList()[$status]]);
        } elseif ($status == CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION) {
            $this->module_id = $requestLog->id;
            $this->deviceToken = $requestObj->nanny->firebase_token;
            $this->title_ar = "بدأت الجلسة";
            $this->title_en = "Session started";
            $this->message_ar = "تم بدأ الجلسة بواسطة " . $requestObj->user->userProfile->fullName;
            $this->message_en = "Session started by " . $requestObj->user->userProfile->fullName;
            $this->to = $requestObj->nany_id;
            $this->route = 'sessionDetailsScreen';
            $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION, 'text' => $requestObj->RequestStatusTrackingList()[$status], 'request_id' => $requestLog->id]);
        } elseif ($status == CustomerRequest::REQUEST_TRACKING_STATUS_ENDED) {
            $this->module_id = $id;
            $this->deviceToken = $requestObj->user->firebase_token;
            $this->title_ar = "انتهت الجلسة";
            $this->title_en = "Session ended";
            $this->message_ar = "تم انهاء الجلسة بواسطة " . $requestObj->nanny->userProfile->fullName;
            $this->message_en = "Session ended by " . $requestObj->nanny->userProfile->fullName;
            $this->to = $requestObj->user_id;
            $this->route = 'orderDetails';
            $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_ENDED, 'text' => $requestObj->RequestStatusTrackingList()[$status]]);
        } elseif ($status == CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL) {
            $this->module_id = $requestLog->id;
            $this->deviceToken = $requestObj->nanny->firebase_token;
            $this->title_ar = "تم الالغاء";
            $this->title_en = "Session cancelled";
            $this->message_ar = "تم الغاء الجلسة بواسطة " . $requestObj->user->userProfile->fullName;
            $this->message_en = "Session cancelled by " . $requestObj->user->userProfile->fullName;
            $this->to = $requestObj->nany_id;
            $this->route = 'sessionDetailsScreen';
            $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_CUSTOMER_CANCEL, 'text' => $requestObj->RequestStatusTrackingList()[$status], 'request_id' => $requestLog->id]);
        } elseif ($status == CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL) {
            $this->module_id = $id;
            $this->deviceToken = $requestObj->user->firebase_token;
            $this->title_ar = "تم الالغاء";
            $this->title_en = "Session cancelled";
            $this->message_ar = "تم الغاء الجلسة بواسطة " . $requestObj->nanny->userProfile->fullName;
            $this->message_en = "Session cancelled by " . $requestObj->nanny->userProfile->fullName;
            $this->to = $requestObj->user_id;
            $this->route = 'orderDetails';
            $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL, 'text' => $requestObj->RequestStatusTrackingList()[$status]]);
        }
        // } elseif ($status == CustomerRequest::REQUEST_TRACKING_STATUS_ADMIN_CANCEL) {
        //     $this->deviceToken = $requestObj->user->firebase_token;
        //     $this->title_ar = "تم الالغاء";
        //     $this->title_en = "Session cancelled";
        //     $this->message_ar = "تم الغاء الجلسة بواسطة " . $requestObj->nanny->userProfile->fullName;
        //     $this->message_en = "Session cancelled by " . $requestObj->nanny->userProfile->fullName;
        //     $this->to = $requestObj->user_id;
        //     $this->route = 'orderDetails';
        //     $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_NANNY_CANCEL, 'text' => $requestObj->RequestStatusTrackingList()[$status]]);
        // }

        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)$status];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function NannyApproval($id)
    {
        $user = User::find()->where(['id' => $id])->one();
        if (!$user) return false;

        $this->notifyToken = true;
        $this->deviceToken = $user->firebase_token;
        $this->key_id = Notifications::TYPE_NANNY_APPROVAL;
        $this->module = 'nanny';
        $this->module_id = $id;
        $this->title_ar = "تم تفعيل حسابك";
        $this->title_en = "Your account has been activated";
        $this->message_ar = "قمنا بمراجعة بياناتك وبإمكانك البدء باستقبال الطلبات";
        $this->message_en = "We have reviewed your data and you can start receiving applications";
        $this->to = $user->id;
        $this->route = 'HomeScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
        return true;
    }

    public function NannyNotApproval($id, $reason)
    {
        $user = User::find()->where(['id' => $id])->one();
        if (!$user) return false;

        $this->notifyToken = true;
        $this->deviceToken = $user->firebase_token;
        $this->key_id = Notifications::TYPE_NANNY_NOT_APPROVED;
        $this->module = 'nanny';
        $this->module_id = $id;
        $this->title_ar = "لم يتم تفعيل حسابك";
        $this->title_en = "Your account has not been activated";
        $this->message_ar = "قمنا بمراجعة بياناتك برجاء مراعاة الملاحظات أدناه: \n" . $reason;
        $this->message_en = "We have reviewed your data please check the below notes: \n" . $reason;
        $this->to = $user->id;
        $this->route = 'CompleteProfile';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
        return true;
    }

    public function ToggleStatus($id)
    {
        $user = User::find()->where(['id' => $id])->one();
        if (!$user) return false;

        $this->notifyToken = true;
        $this->deviceToken = $user->firebase_token;
        $this->key_id = Notifications::TYPE_NANNY_APPROVAL;
        $this->module = 'nanny';
        $this->module_id = $id;
        $status = $user->status == User::STATUS_ACTIVE ? "تفعيلة" : "تعطيلة";
        $this->title_ar = "تم " . $status . " حسابك ";
        $status = $user->status == User::STATUS_ACTIVE ? "activated" : "deactivated";
        $this->title_en = "Your account has been " . $status;
        $this->message_ar = "لمزيد من المعلومات برجاء التواصل مع الدعم";
        $this->message_en = "For more information, please contact support.";
        $this->to = $user->id;
        $this->route = 'HomeScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
        return true;
    }

    public function Rate($id)
    {
        $rate = Rate::find()->where(['id' => $id])->one();
        if (!$rate) return false;

        $user = User::findOne($rate->user_id);

        if($rate->rater->user_type == 0){            
            $requestObj = RequestLog::find()->where(['nany_id' => $rate->user_id, 'request_id' => $rate->request_id])->one();
        }else{  
            $requestObj = CustomerRequest::find()->where(['id' => $rate->request_id])->one();
        }

        $this->notifyToken = true;
        $this->deviceToken = $rate->user->firebase_token;
        $this->key_id = Notifications::TYPE_RATE;
        $this->module = 'rate';
        $this->module_id = $requestObj->id;
        $this->title_ar = "تقييم جديد";
        $this->title_en = "New rate";
        $this->message_ar = "قام  " . $rate->rater->userProfile->fullName . " باضافة تقييم لحسابك";
        $this->message_en = $rate->rater->userProfile->fullName . " added a rate to your account";
        $this->to = $rate->user_id;
        $this->route = ($rate->rater->user_type == 0) ? 'sessionDetailsScreen' : 'orderDetails';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
        return true;
    }

    public function NewChatMessage($id)
    {
        $message = Message::find()->where(['id' => $id])->one();
        if (!$message) return false;

        $this->notifyToken = true;
        $this->key_id = Notifications::TYPE_CHAT;
        $this->module = 'chat';
        $this->module_id = $id;

        //request_id
        $this->title_ar = "رسالة جديدة";
        $this->title_en = "New message";

        $user = User::findOne($message->sender_id);

        if ($user->user_type == 0) {
            $this->deviceToken = $message->conversation->nanny->firebase_token;

            $this->message_ar = "قام  " . $message->conversation->customer->userProfile->fullName . " بارسال رسالة جديدة لك";
            $this->message_en = $message->conversation->customer->userProfile->fullName . " sent new message to you";
            $this->to = $message->conversation->nanny_id;
            $requestLog = RequestLog::find()->where(['request_id' => $message->conversation->request_id])->one();
            $this->request_id = $requestLog->id;
        } else {
            $this->deviceToken = $message->conversation->customer->firebase_token;

            $this->message_ar = "قام  " . $message->conversation->nanny->userProfile->fullName . " بارسال رسالة جديدة لك";
            $this->message_en = $message->conversation->nanny->userProfile->fullName . " sent new message to you";
            $this->to = $message->conversation->customer_id;
            $this->request_id = $message->conversation->request_id;
        }

        $this->route = 'chatScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_id' => (string)$this->request_id];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
        return true;
    }

    public function extendSession($id)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $extendedSession = ExtendedRequests::find()->where(['request_id' => $id])->orderBy('id DESC')->one();

        $requestLog = RequestLog::find()->where(['request_id' => $requestObj->id, 'nany_id' => $requestObj->nany_id])->one();

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nanny->firebase_token;
        $this->key_id = Notifications::TYPE_EXTEND_SESSION;
        $this->module = 'request';
        $this->module_id = $requestLog->id;
        $this->title_ar = "زيادة وقت الجلسة";
        $this->title_en = "Extend Session Period";
        $this->message_ar = $requestObj->user->userProfile->fullName . " يطلب زيادة وقت الجلسه لمدة " . $extendedSession->extended_period;
        $this->message_en = $requestObj->user->userProfile->fullName . " requested to extend session " . $extendedSession->extended_period . " more";
        $this->to = $requestObj->nany_id;
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION];
        $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION, 'text' => $requestObj->statuses()[CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function UpdateExtendsessionStatus($id, $status)
    {
        $requestObj = RequestLog::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $customerRequest = CustomerRequest::findOne($requestObj->request_id);

        $this->notifyToken = true;
        $this->key_id = Notifications::TYPE_UPDATE_EXTEND_SESSSION_STATUS;
        $this->module = 'request';
        $this->module_id = $customerRequest->id;

        if ($status == CustomerRequest::EXTENDED_SESSION_STATUS_ACCEPTED) {
            $this->deviceToken = $customerRequest->user->firebase_token;
            $this->title_ar = "تم القبول";
            $this->title_en = "Extend Session Accepted";
            $this->message_ar = $customerRequest->nanny->userProfile->fullName . " قامت بقبول طلب زيادة وقت الجلسة";
            $this->message_en = $customerRequest->nanny->userProfile->fullName . " accepted extend session period request";
            $this->to = $customerRequest->user_id;
            $this->payload = json_encode(['id' => CustomerRequest::EXTENDED_SESSION_STATUS_ACCEPTED, 'text' => $customerRequest->RequestStatusExtendSessionList()[$status]]);
        } elseif ($status == CustomerRequest::EXTENDED_SESSION_STATUS_REJECTED) {
            $this->deviceToken = $customerRequest->user->firebase_token;
            $this->title_ar = "تم الرفض";
            $this->title_en = "Extend Session Rejected";
            $this->message_ar = $customerRequest->nanny->userProfile->fullName . " قامت رفض طلب زيادة وقت الجلسة";
            $this->message_en = $customerRequest->nanny->userProfile->fullName . " rejected extend session period request";
            $this->to = $customerRequest->user_id;
            $this->payload = json_encode(['id' => CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION, 'text' => $customerRequest->RequestStatusExtendSessionList()[$status]]);
        }

        $this->route = 'orderDetails';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)$status];
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();

        return true;
    }

    public function customerFullRefund($id, $status)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->user->firebase_token;
        $this->key_id = Notifications::TYPE_SESSION_FULL_REFUND;
        $this->module = 'request';
        $this->module_id = $requestObj->id;

        $this->title_ar = "ارجاع كامل لمبلغ الجلسة";
        $this->title_en = "ٍSession total price refunded";
        $this->message_ar = "سيتم ارجاع كامل مبلغ الجلسة  " . $requestObj->paid_amount . " الى حسابك";
        $this->message_en = "Session total price " . $requestObj->paid_amount . " will be refunded to your account";

        $this->to = $requestObj->user_id;
        $this->route = 'orderDetails';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)$status];
        $this->payload = json_encode(['id' => $status, 'text' => $requestObj->statuses()[$status]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function customerNannyHalfRefund($id, $status)
    {
        $this->customerHalfRefund($id, $status);
        $this->nannyHalfRefund($id, $status);
    }

    public function customerHalfRefund($id, $status)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->user->firebase_token;
        $this->key_id = Notifications::TYPE_SESSION_HALF_REFUND;
        $this->module = 'request';
        $this->module_id = $requestObj->id;

        $this->title_ar = "ارجاع نصف مبلغ الجلسة";
        $this->title_en = "ٍSession half price refunded";
        $this->message_ar = "سيتم ارجاع نصف مبلغ الجلسة  " . $requestObj->customer_refunded_amount . " الى حسابك";
        $this->message_en = "Session half price " . $requestObj->customer_refunded_amount . " will be refunded to your account";

        $this->to = $requestObj->user_id;
        $this->route = 'orderDetails';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)$status];
        $this->payload = json_encode(['id' => $status, 'text' => $requestObj->statuses()[$status]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }

    public function nannyHalfRefund($id, $status)
    {
        $requestObj = CustomerRequest::find()->where(['id' => $id])->one();
        if (!$requestObj) return false;

        // $settings = Settings::findOne(1);

        // if ($settings->service_fee_type == Settings::SERVICE_FEE_TYPE_FIXED) {
        //     $fees = $settings->service_fees;
        // } else {
        //     $fees = $requestObj->refunded_amount * $settings->service_fees / 100;
        // }

        $requestLog = RequestLog::find()->where(['request_id' => $requestObj->id, 'nany_id' => $requestObj->nany_id])->one();

        $this->notifyToken = true;
        $this->deviceToken = $requestObj->nanny->firebase_token;
        $this->key_id = Notifications::TYPE_SESSION_HALF_REFUND;
        $this->module = 'request';
        $this->module_id = $requestLog->id;

        $this->title_ar = "ارجاع نصف مبلغ الجلسة";
        $this->title_en = "ٍSession half price refunded";
        $this->message_ar = "سيتم ارجاع نصف مبلغ الجلسة  " . $requestObj->refunded_amount . " الى محفظتك";
        $this->message_en = "Session half price " . $requestObj->refunded_amount . " will be refunded to your wallet";

        $this->to = $requestObj->nany_id;
        $this->route = 'sessionDetailsScreen';
        $this->data = ['key' => (string)$this->key_id, 'type' => $this->module, 'id' => (string)$this->module_id, 'route' => $this->route, 'request_status' => (string)$status];
        $this->payload = json_encode(['id' => $status, 'text' => $requestObj->statuses()[$status]]);
        $this->seen = Notifications::NOTIFICATION_NOT_SEEN;
        $this->Send();
    }
}