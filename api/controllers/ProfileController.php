<?php

namespace api\controllers;

use common\models\CompanyProfile;
use common\models\User;
use Yii;
use api\models\UserForm;
use api\helpers\ImageHelper;
use api\models\ChangePassword;
use api\helpers\ResponseHelper;
use api\resources\UserResource;
use yii\base\InvalidParamException;
use common\models\CompleteProfileData;

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
