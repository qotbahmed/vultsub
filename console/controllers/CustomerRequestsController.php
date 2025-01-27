<?php

namespace console\controllers;


use backend\models\Settings;
use common\helpers\NotificationHelper;
use common\models\Category;
use common\models\CustomerRequest;
use yii\console\Controller;

class CustomerRequestsController extends Controller
{
//$schedule->exec('/usr/local/bin/php /var/www/html/NanyBackend/yii cron/run')->daily();
//php yii customer-requests/notifications

//   php yii customer-requests/category

    public function actionCategory()
    {
        $model = new Category();
        $model->name= strtotime(date('H:i'))."";
        $model->created_by=1;
        $model->updated_by=1;
        if (!$model->save()) {
            echo $model->errors."";
        }
        echo "\n Inserted \n";
    }

    public function actionNotifications()
    {
        echo' \n  *******   Action Notifications ********   \n ';

        $currentTime = strtotime(date('H:i'));
        $sessions = CustomerRequest::find()->where(['request_tracking_status'=>CustomerRequest::REQUEST_TRACKING_STATUS_IN_SESSION,
            'notified'=>CustomerRequest::SESSION_NOT_NOTIFIED])->all();

        foreach ($sessions as $session) {

            $sessionStart = strtotime($session->session_time);
            $sessionPeriod = $session->total_session_period * 60; // Convert to seconds
            $sessionEndTime = $sessionStart + $sessionPeriod;

            $remainingTime = $sessionEndTime - $currentTime;

            $settings = Settings::findOne(1);
            echo'  remainingTime = ' .$remainingTime;
            echo'  $settings Time = ' .$settings->end_session_alarm * 60;

            if ($remainingTime <= ($settings->end_session_alarm * 60)) {

                $session->notified = CustomerRequest::SESSION_NOTIFIED;
                if (!$session->save()) {
                    \Yii::error('Error saving session record: ' . print_r($session->getErrors(), true));
                    echo'\n '. $session->getErrors().'\n ';
                }
                NotificationHelper::instance()->MinutesRemaining($session->id);
                NotificationHelper::instance()->MinutesRemainingNanny($session->id);
            }
        }
        echo "\n done \n ";
    }


}