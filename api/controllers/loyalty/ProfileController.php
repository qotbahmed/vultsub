<?php

namespace api\controllers\loyalty;

use api\controllers\MyActiveController;
use api\controllers\MyActiveUnAuthController;
use api\controllers\MyRestUnAuthController;
use api\helpers\ProfileHelper;
use backend\models\Settings;
use common\models\PointsLogs;
use common\models\User;
use common\models\UserProfile;
use Yii;
use api\models\UserForm;
use api\helpers\ImageHelper;
use api\models\ChangePassword;
use api\helpers\ResponseHelper;
use api\resources\UserResource;
use yii\base\InvalidParamException;

class ProfileController extends MyRestUnAuthController
{

    public function actionIndex()
    {
        $params = \Yii::$app->request->get();

        $user = User::findOne(['access_token' => $params['access_token']]);
        if ($user) {
            return ResponseHelper::sendSuccessResponse([
                'name' => $user->userProfile->getFullName(),
                'points' => $user->userProfile->points_num,
            ]);
        } else {
            return ResponseHelper::sendFailedResponse(Yii::t('common', "User not found"));
        }
    }

    public function actionDecrasePoints()
    {
        $params = \Yii::$app->request->post();
        $points = $params['points'] ?? 0;

        $user = User::findOne(['access_token' => $params['access_token']]);

        if ($user) {
            $user= UserProfile::findOne(['user_id' => $user->id]);

            if ($user->points_num < $points) {
                return ResponseHelper::sendFailedResponse(Yii::t('common', "Not enough points"));
            }
            $user->points_num -= $points;
            if ($user->save()) {
                return ResponseHelper::sendSuccessResponse(Yii::t('common', "Points decreased successfully."));
            }
        }
            return ResponseHelper::sendFailedResponse(Yii::t('common', "User not found"));

    }

}
