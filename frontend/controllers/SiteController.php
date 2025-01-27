<?php

namespace frontend\controllers;

use common\models\User;
use frontend\controllers\FrontController;
use Yii;
use yii\filters\PageCache;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\View;

/**
 * Site controller
 */
class SiteController extends FrontController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * @return Response
     */
    public function actionIndex(){

        if( (\Yii::$app->user->can('user')&& Yii::$app->user->identity->user_type == User::USER_TYPE_PARENT)){
            return $this->redirect(['/profile/index']);

        }else{
            //logout
            Yii::$app->user->logout();
            return $this->redirect(['/sign-in/login']);
        }

        return $this->render($view);

    }

}
