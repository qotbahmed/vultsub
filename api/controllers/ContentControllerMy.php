<?php

namespace api\controllers;

use api\helpers\ResponseHelper;
use api\resources\PageResource;
use api\resources\SettingsResource;
use common\models\Page;
use Yii;

class ContentControllerMy extends MyRestUnAuthController
{
    public function beforeAction($action)
    {
        if (isset($_REQUEST['lang']) && $_REQUEST['lang'] == 'ar') {
            \Yii::$app->language = 'ar';
        }
        return parent::beforeAction($action);
    }




}
