<?php

namespace api\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class RestController extends Controller
{
    public $defaultPageSize = 15;
    public $pageSize = 15;

    public $pageSizeLimit = [10, 200];

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function beforeAction($action)
    {
        $language = \Yii::$app->request->headers->get('Accept-Language', 'en');
        \Yii::$app->language = $language;

        return parent::beforeAction($action);
    }

    public static function allowedDomains()
    {
        return [
            // '*', // star allows all domains
            'http://127.0.0.1:3000',
            'http://localhost:3000',
            'http://joyjoin.tonesapps.com',
            'https://joyjoin.tonesapps.com'

        ];
    }

    public function  behaviors()
    {
        $behaviors = parent::behaviors();
        // remove authentication filter if there is one
        unset($behaviors['authenticator']);

        // add CORS filter before authentication
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => self::allowedDomains(),
                'Access-Control-Request-Method' => ['POST'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // Put in a bearer auth authentication filter
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ]
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        return $behaviors;
    }
}
