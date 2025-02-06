<?php

namespace api\controllers;

use api\resources\SettingsResource;
use api\resources\SponsorResource;
use Yii;
use api\helpers\ResponseHelper;

class LookupsController extends MyRestUnAuthController
{



    public function actionSponsors()
    {
        $sponsors = SponsorResource::find()->all();
        return ResponseHelper::sendSuccessResponse($sponsors);
    }
    public function actionSettings()
    {
        $settings = SettingsResource::find()->all();
        return ResponseHelper::sendSuccessResponse($settings);
    }





}
