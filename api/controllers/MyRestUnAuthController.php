<?php

namespace api\controllers;

use api\helpers\ApiHelper;
use yii\rest\Controller;

class MyRestUnAuthController extends Controller
{
    public static function allowedDomains()
    {
       return ApiHelper::allowedDomains();
    }


    public function  behaviors()
    {
        $behaviors = parent::behaviors();
        // remove authentication filter if there is one
        unset($behaviors['authenticator']);

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => self::allowedDomains(),
                'Access-Control-Request-Method' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (isset($_REQUEST['lang']) && $_REQUEST['lang'] == 'ar') {
            \Yii::$app->language = 'ar';
        }
        return parent::beforeAction($action);
    }
}
