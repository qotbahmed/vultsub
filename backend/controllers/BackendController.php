<?php

namespace backend\controllers;
use Yii;

use webvimark\behaviors\multilanguage\MultiLanguageHelper;
use yii\web\ForbiddenHttpException;

/**
 * Site controller
 */
class BackendController extends \yii\web\Controller
{
    public  $University;
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function init()
    {
        MultiLanguageHelper::catchLanguage();
        \Yii::$app->language= 'ar';
        parent::init();
    }

    //check if sub admin apply roles and permission
    public function beforeAction($action)
    {

        if ( !Yii::$app->user->isGuest && Yii::$app->user->can('customRole')){
               // $this->layout = 'roles';

                $controller = Yii::$app->controller->id;
                $allowedControllers = ['sign-in','site','helper','charts'];
                $controllerAction = Yii::$app->controller->action->id;


            $allowedActions = ['image-upload','image-delete','avatar-upload','avatar-delete','galleryApi','file-upload',
                    'file-delete'];

                if(in_array($controller, $allowedControllers)
                    || in_array($controllerAction, $allowedActions)
                    || ($controller == 'content-editor'))// any extra exception to be passes here
                {
                    return true;
                }

            $permissions = array_keys(Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->identity->id));
                if(!in_array($controller.'_'.$controllerAction ,$permissions) && !in_array($controller ,$permissions))
                {
                    throw new ForbiddenHttpException(\Yii::t('common','You don\'t have access on this page'));
                }
                return true;
            }


        return parent::beforeAction($action);
    }



}
