<?php

namespace api\controllers;

use api\helpers\ImageHelper;
use cheatsheet\Time;
use common\models\CompanyProfile;
use yii\base\DynamicModel;

use Yii;
use common\models\User;
use api\models\UserSignup;
use common\models\UserToken;
use api\helpers\ResponseHelper;
use api\resources\UserResource;
use api\controllers\MyRestController;
use common\commands\SendEmailCommand;
use yii\base\InvalidParamException;

class UserController extends MyRestUnAuthController
{
    public $prefix = '966';

    public function actionLogin()
    {
        $params = \Yii::$app->request->post();

        if (isset($params['identity']) && isset($params['password'])) {
            $user = UserResource::find()
                ->active()
                ->andWhere(['email' => $params['identity']])
                ->one();
//            return ResponseHelper::sendSuccessResponse($user);
            if (!$user) {
                return ResponseHelper::sendFailedResponse( Yii::t('common', 'Please check your data and validate your email.'), 400);
            }
            if ($user->status !== User::STATUS_ACTIVE) {
                return ResponseHelper::sendFailedResponse(
                  Yii::t('common', 'Your account is not active. Please activate your account.'),
                    403
                );
            }
            $valid_password = Yii::$app->getSecurity()->validatePassword($params['password'], $user->password_hash);
            if ($valid_password) {
                $user->logged_at = time();
                $user->save(false);
                $data = ['token' => $user->access_token, 'profile' => $user];
                return ResponseHelper::sendSuccessResponse($data);
            } else {
                return ResponseHelper::sendFailedResponse( Yii::t('common', 'Your login data is not correct.'));
            }
        } else {
            return ResponseHelper::sendFailedResponse( Yii::t('common', 'Your login data is not correct.'));
        }
    }

    public function actionSignup()
    {
        $params = \Yii::$app->request->post();

//        return $params;
        $model = new UserSignup();
        $model->load(['UserSignup' => $params]);

        $registerUser = $model->signup();


        if ($registerUser['status']) {
            $user = UserResource::find()->where(['id' => $registerUser['user']->id])->one();
            if(YII_ENV_DEV){
                $email = env('ROBOT_EMAIL');
                $token = UserToken::create($user->id, UserToken::TYPE_ACTIVATION, Time::SECONDS_IN_AN_HOUR,'1111',$model->email);
            }else{
                $token = UserToken::create($user->id, UserToken::TYPE_ACTIVATION, Time::SECONDS_IN_AN_HOUR,null,$model->email);
                $email= $user->email ;
                $token = $token->otp;
                $name= $user->userProfile->firstname . ' ' . $user->userProfile->lastname;

                Yii::$app->mailer->compose('new_user_verify_email', [
                    'name' => $name,
                    'otp' => $token,
                ])->setFrom([Yii::$app->params['adminEmail'] => "zakeer@mail.com" . ' Team'])
                    ->setTo($email)
                    ->setSubject(Yii::t('common', 'Verify email for {name}', ['name' => $name]))
                    ->send();



            }
            return ResponseHelper::sendSuccessResponse($user);
        } else {
            return ResponseHelper::sendFailedResponse(array_merge($model->getFirstErrors(), $registerUser['errors']));
        }
    }

    public function actionVerify()
    {
        $params = \Yii::$app->request->post();

        $email = $params['email'];
        $otp = $params['otp'];

        $user = UserResource::find()->where(['email' => $email , 'status'=>User::STATUS_ACTIVE])->one();
        if($user) return ResponseHelper::sendSuccessResponse(['Message'=> "User is already verified"], 200);



        $model = DynamicModel::validateData(compact('email', 'otp'), [
            [['email', 'otp'], 'required'],
            ['email', 'email'],
        ]);

        if ($model->hasErrors()) {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors());
        }

        $user = User::findOne(['email' => $email]);
        if (!$user) {
            return ResponseHelper::sendFailedResponse(\Yii::t('common', 'Please check the entered data'));
        }
        $otpObj = UserToken::find()
            ->byType(UserToken::TYPE_ACTIVATION)
            ->byOtp($otp)
            ->byEmail($email)
            ->notExpired()
            ->one();

        if ($otpObj) {
            $user = UserResource::find()->where(['id' => $otpObj->user_id])->one();
            $user->updateAttributes([
                'status' => User::STATUS_ACTIVE,
            ]);
            $user->logged_at = time();
            $user->save(false);
            $otpObj->delete();

            return ResponseHelper::sendSuccessResponse([
                'message' => Yii::t('common', 'Your account has been successfully activated.'),
                'token' => $user->access_token,
                'profile' => $user
            ]);
        } else {
            return ResponseHelper::sendFailedResponse(Yii::t('common', 'OTP not valid.'));
        }
    }

    public function actionResendVerifyCode()
    {
        $params = \Yii::$app->request->post();
        $email = $params['email'];
        $model = DynamicModel::validateData(['email' => $email], [
            ['email', 'required'],
            ['email', 'email'],
        ]);

        if ($model->hasErrors()) {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors());
        }

        $user = User::findOne(['email' => $email]);
        if ($user) {
            $token = UserToken::findOne(['user_id' => $user->id, 'type' => UserToken::TYPE_ACTIVATION]);
            if ($token) {
                $token->updateAttributes(['otp' => UserToken::generateOtp(UserToken::OTP_LENGTH),
                    'expire_at' => time() + Time::SECONDS_IN_AN_HOUR]);
                $token = $token->otp;

            }else{
                $token = UserToken::create($user->id, UserToken::TYPE_ACTIVATION, Time::SECONDS_IN_AN_HOUR,null,$model->email);
                $token = $token->otp;

            }

            if ($user->save()) {

                $name= $user->userProfile->firstname . ' ' . $user->userProfile->lastname;

                Yii::$app->mailer->compose('new_user_verify_email', [
                    'name' => $name,
                    'otp' => $token,
                ])->setFrom([Yii::$app->params['adminEmail'] => "zakeer@mail.com" . ' Team'])
                    ->setTo($email)
                    ->setSubject(Yii::t('common', 'Verify email for {name}', ['name' => $name]))
                    ->send();

                $message = \Yii::t('common', 'verify email code sent successfully.');
                return ResponseHelper::sendSuccessResponse($message);
            }
        }
        return ResponseHelper::sendFailedResponse( \Yii::t('common', 'Please check the entered data'), 404);
    }

    public function actionRequestResetPassword()
    {
        $params = \Yii::$app->request->post();
        $email = $params['email'];
        $model = DynamicModel::validateData(['email' => $email], [
            ['email', 'required'],
            ['email', 'email'],
        ]);

        if ($model->hasErrors()) {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors());
        }

        $user = User::findOne(['email' => $email]);
        if ($user) {
            $token = UserToken::findOne(['user_id' => $user->id, 'type' => UserToken::TYPE_PASSWORD_RESET]);
            if ($token) {
                $token->updateAttributes(['otp' => UserToken::generateOtp(UserToken::OTP_LENGTH),'expire_at' => time() + Time::SECONDS_IN_AN_HOUR]);
                $token = $token->otp;

            }else{
                $token = UserToken::create($user->id, UserToken::TYPE_PASSWORD_RESET, Time::SECONDS_IN_AN_HOUR,null,$model->email);
                $token = $token->otp;

            }

            if ($user->save()) {

                $name= $user->userProfile->firstname . ' ' . $user->userProfile->lastname;

                Yii::$app->mailer->compose('reset_user_password',[
                    'name' => $name,
                    'otp' => $token,
                ])->setFrom([Yii::$app->params['adminEmail'] => "zakeer@mail.com" . ' Team'])
                    ->setTo($email)
                    ->setSubject(Yii::t('common', 'Password reset for {name}', ['name' => $name]))
                    ->send();


                $message = \Yii::t('common', 'Email reset password sent successfully');
                return ResponseHelper::sendSuccessResponse($message);
            }
        }
        return ResponseHelper::sendFailedResponse( \Yii::t('common', 'Please check the entered data'), 404);
    }

    public function actionVerifyResetPasswordToken()
    {
        $params = \Yii::$app->request->post();

        $email = $params['email'];
        $otp = $params['otp'];
        $model = DynamicModel::validateData(compact('email', 'otp'), [
            [['email', 'otp'], 'required'],
            ['email', 'email'],
        ]);

        if ($model->hasErrors()) {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors(), 400);
        }

        $user = User::findOne(['email' => $email]);
        if (!$user) {
            return ResponseHelper::sendFailedResponse(\Yii::t('common', 'Please check the entered data'));
        }

        $otpObj = UserToken::find()
            ->where(['user_id' => $user->id])
            ->byType(UserToken::TYPE_PASSWORD_RESET)
            ->byOtp($otp)
            ->notExpired()
            ->one();

        if ($otpObj) {
            return ResponseHelper::sendSuccessResponse(Yii::t('common', 'OTP is valid.'));
        } else {
            return ResponseHelper::sendFailedResponse( Yii::t('common', 'Token not valid.'));
        }
    }

    public function actionResetPassword()
    {
        $params = \Yii::$app->request->post();
        $email = $params['email'];
        $otp = $params['otp'];
        $password = $params['password'];
        $confirm_password = $params['confirm_password'];
        $model = DynamicModel::validateData(compact('email', 'otp', 'password', 'confirm_password'), [
            [['email', 'otp', 'password', 'confirm_password'], 'required'],
            ['email', 'email'],
            [
                'confirm_password', 'compare', 'compareAttribute' => 'password',
                'message' => Yii::t('common', "Passwords don't match"),
            ],
        ]);

        if ($model->hasErrors()) {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors());
        }

        $user = User::findOne(['email' => $email]);
        if (!$user) {
            return ResponseHelper::sendFailedResponse(\Yii::t('common', 'Please check the entered data'), 404);
        }

        $token = UserToken::find()->where(['user_id' => $user->id])
            ->byType(UserToken::TYPE_PASSWORD_RESET)
            ->byOtp($otp)
            ->notExpired()
            ->one();
        if ($token) {
            $user = UserResource::find()->where(['id' => $token->user_id])->one();
            $user->password = $model->password;
            if ($user->save()) {
                $token->delete();
                return ResponseHelper::sendSuccessResponse([
                    'message' => Yii::t('common', 'Your password has been reset successfully.'),
                    'token' => $user->access_token,
                    'profile' => $user
                ]);
            }
        } else {
            return ResponseHelper::sendFailedResponse( Yii::t('common', 'Token not valid.'));
        }
    }

    public function actionTest()
    {
        return ResponseHelper::sendSuccessResponse("success");
    }
}
