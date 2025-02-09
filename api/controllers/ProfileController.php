<?php

namespace api\controllers;

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

class ProfileController extends MyActiveController
{

    public $modelClass = UserResource::class;

    public function actionIndex()
    {
        $user = UserResource::findOne(\Yii::$app->user->identity->id);
        return ResponseHelper::sendSuccessResponse($user);
    }


    public function actionUpdate()
    {
        $user = UserResource::findOne(\Yii::$app->user->identity->id);
        $params = \Yii::$app->request->post();
        $model = new UserForm();
        $model->username =  $user->email;
        $model->email =  $user->email;
        if ($model->load(['UserForm' => $params]) && $model->save()) {
            if ($model->binary_picture) {
                $userProfile = $user->userProfile;
                try {
                    $filename = ImageHelper::Base64Image($model->binary_picture);
                    $userProfile->avatar_base_url = \Yii::getAlias('@storageUrl') . '/source/';
                    $userProfile->avatar_path = 'profile/' . $filename;
                } catch (InvalidParamException $e) {
                    return ResponseHelper::sendFailedResponse(['binary_picture' => $e->getMessage()]);
                }

                if (!$userProfile->save(false)) {
                    return ResponseHelper::sendFailedResponse($userProfile->getFirstErrors());
                }
            }

            $user = UserResource::findOne(\Yii::$app->user->identity->id);
            return ResponseHelper::sendSuccessResponse($user);
        } else {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors() ?: ['error' => Yii::t('common', "Invalid access")]);
        }
    }
    public function actionCheckPoint()
    {
        $params = \Yii::$app->request->post();
        $user_id = Yii::$app->user->identity->id;
        $total_time = $params['time'] ?? 0;
        $page_count = $params['page_count'] ?? 0; // Number of pages read

        $user = UserProfile::findOne(['user_id' => $user_id]);

        if (isset($params['surah'], $params['ayah_num'], $params['page_num'])) {

            $settings = Settings::find()->select([
                'points_per_second',
                'daily_points',
                'reading_points_delay',
                'max_daily_points_per_user'
            ])->one();

            $todayPoints = PointsLogs::find()
                ->where(['user_id' => $user_id])
                ->andWhere(['>=', 'created_at', date('Y-m-d 00:00:00')])
                ->sum('points_num') ?? 0;

            $expected_pages_read = floor($total_time / $settings->reading_points_delay);
            $valid_pages = min($expected_pages_read, $page_count);

//            if ($valid_pages < 1) {
//                return ResponseHelper::sendFailedResponse(['error' => Yii::t('common', 'Reading time is too short to earn points.')]);
//            }

            $earned_time = $valid_pages * $settings->reading_points_delay; // Time that is valid
            $earned_points = $earned_time * $settings->points_per_second;

            $new_total_points = $todayPoints + $earned_points;
            if ($new_total_points > $settings->max_daily_points_per_user) {
                $earned_points = max(0, $settings->max_daily_points_per_user - $todayPoints);
            }

            $user->surah = $params['surah'] ?? '';
            $user->ayah_num = $params['ayah_num'] ?? 0;
            $user->points_num += $earned_points ?? 0;
            $user->page_num = $params['page_num'] ?? 0;

            if ($user->save()) {
                // **Log the earned points**
                $log = new PointsLogs();
                $log->user_id = $user->user_id;
                $log->user_name = $user->firstname;
                $log->user_mobile = $user->user->mobile;
                $log->points_num = $earned_points;
                $log->type = PointsLogs::TYPE_ADD;
                $log->page_num = $params['page_count']??0;
                $log->time = $earned_time; // Log valid time

                if (!$log->save()) {
                    return ResponseHelper::sendFailedResponse($log->getFirstErrors());
                }
                return ResponseHelper::sendSuccessResponse(Yii::t('common', "Updated Successfully."));
            } else {
                return ResponseHelper::sendFailedResponse($user->getFirstErrors());
            }
        } else {
            return ResponseHelper::sendFailedResponse(['error' => Yii::t('common', "Missing required parameters")]);
        }
    }

    public function actionCalculatePoints()
    {
        $params = Yii::$app->request->post();
        $user_id = Yii::$app->user->identity->id;
        $total_time = $params['total_time'] ?? 0;
        $page_numbers = $params['page_numbers'] ?? 0;

        $settings = Settings::find()->select([
            'points_per_second',
            'daily_points',
            'reading_points_delay',
            'max_daily_points_per_user'
        ])->one();

        if (!$settings) {
            return ResponseHelper::sendFailedResponse(['error' => Yii::t('common', 'Settings not found.')]);
        }

        $todayPoints = PointsLogs::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['>=', 'created_at', date('Y-m-d 00:00:00')])
            ->sum('points_num') ?? 0;

        $expected_time = $params['page_count'] * $settings->reading_points_delay;

        if ($total_time < $expected_time) {
            return ResponseHelper::sendFailedResponse([
                'error' => Yii::t('common', 'Reading time is too short for the pages read.')
            ]);
        }

        $earned_points = $total_time * $settings->points_per_second;
        $new_total_points = $todayPoints + $earned_points;

        if ($new_total_points > $settings->max_daily_points_per_user) {
            $earned_points = max(0, $settings->max_daily_points_per_user - $todayPoints);
        }

        return ResponseHelper::sendSuccessResponse([
            'earned_points' => $earned_points,
            'total_today' => $new_total_points
        ]);
    }
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        $params = \Yii::$app->request->post();
        if ($model->load(['ChangePassword' => $params]) && $model->save()) {
            return ResponseHelper::sendSuccessResponse(Yii::t('common', "Password Updated Successfully."));
        } else {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors());
        }
    }

}
