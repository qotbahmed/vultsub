<?php

namespace api\controllers;

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





}
