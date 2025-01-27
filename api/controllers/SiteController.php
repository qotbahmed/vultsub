<?php

namespace api\controllers;
use yii\web\Response;

use Yii;
use Exception;
use api\helpers\ResponseHelper;
use yii\web\NotFoundHttpException;
use api\resources\SettingsResource;
use api\controllers\MyRestController;
use api\resources\SupportTeamResource;

class SiteController extends MyRestController
{

    public function actionIndex()
    {
        return 'Welcome...';
    }


    public function actionSettings()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $faq = SettingsResource::findOne(1);
        return ResponseHelper::sendSuccessResponse($faq);
    }

    public function beforeAction($action)
    {
        if (isset($_REQUEST['lang']) && $_REQUEST['lang'] == 'ar') {
            Yii::$app->language = 'ar';
        }
        return parent::beforeAction($action);
    }


    public function actionTerms()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $page = "Terms and Conditions";
        return ResponseHelper::sendSuccessResponse($page);
    }

    public function actionError()
{
    $exception = Yii::$app->errorHandler->exception;

    if ($exception !== null) {
        Yii::$app->response->statusCode = $exception->statusCode;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => 'error',
            'message' => $exception->getMessage(),
        ];
    }
}

}
