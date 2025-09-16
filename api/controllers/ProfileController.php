<?php

namespace api\controllers;

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
        $model->username = $user->email;
        $model->email = $user->email;
        if ($model->load(['UserForm' => $params]) && $model->save()) {
            if ($model->binary_picture) {
                $userProfile = $user->userProfile;
                try {
                    $filename = ImageHelper::Base64Image($model->binary_picture);
                    $userProfile->avatar_base_url = \Yii::getAlias('@storageUrl') . '/source/';
                    $userProfile->avatar_path = 'profile/' . $filename;
                } catch (InvalidParamException $e) {
                    return ResponseHelper::sendFailedResponse( $e->getMessage());
                }

                if (!$userProfile->save(false)) {
                    return ResponseHelper::sendFailedResponse($userProfile->getFirstErrors());
                }
            }

            $user = UserResource::findOne(\Yii::$app->user->identity->id);
            return ResponseHelper::sendSuccessResponse($user);
        } else {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors() ?:  Yii::t('common', "Invalid access"));
        }
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
    public function actionDelete()
    {
        $user = User::find()->where(['id'=> \Yii::$app->user->getId() ])->andWhere(['!=', 'status', User::STATUS_DELETED])->one();

        return ProfileHelper::instance()->Delete($user);
    }


}
