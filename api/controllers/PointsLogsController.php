<?php

namespace api\controllers;

use common\models\PointsLogs;
use common\models\User;
use common\models\UserProfile;
use Yii;
use api\models\UserForm;
use api\helpers\ImageHelper;
use api\models\ChangePassword;
use api\helpers\ResponseHelper;
use api\resources\PointsLogsResource;
use yii\base\InvalidParamException;

class PointsLogsController extends MyActiveController
{

    public $modelClass = PointsLogsResource::class;

    public function actionIndex()
    {
        $pointsLogs = PointsLogsResource::find()->where(['user_id',\Yii::$app->user->identity->id])->all();
        return ResponseHelper::sendSuccessResponse($pointsLogs);
    }





}
