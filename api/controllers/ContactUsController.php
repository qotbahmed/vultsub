<?php

namespace api\controllers;

use Yii;
use common\models\ContactUs;
use api\helpers\ResponseHelper;
use common\commands\SendEmailCommand;

class ContactUsController extends MyActiveController
{
    public $modelClass = ContactUs::class;

    public function actionCreate()
    {
        $params = \Yii::$app->request->post();
        $model = new ContactUs();

        if (!Yii::$app->user->isGuest) {
            $model->name = Yii::$app->user->identity->userProfile->firstname;
            $model->email = Yii::$app->user->identity->email;
        }

        if ($model->load(['ContactUs' => $params]) && $model->save()) {
            Yii::$app->commandBus->handle(new SendEmailCommand([
                'to' => $model->email,
                'subject' => Yii::t('common', 'Contact request'),
                'view' => 'contact-request',
                'params' => [
                    'data' => $model,
                ]
            ]));
            return ResponseHelper::sendSuccessResponse(['message' => 'Sent successfully.']);
        } else {
            return ResponseHelper::sendFailedResponse($model->getFirstErrors(), 400);
        }
    }
}
