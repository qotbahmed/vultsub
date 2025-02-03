<?php

namespace api\controllers;

use api\resources\FaqResource;
use api\resources\SponsorResource;
use common\helpers\Helper;
use Yii;
use api\helpers\ResponseHelper;
use api\resources\CountryResource;

class LookupsControllerMy extends MyRestUnAuthController
{



    public function actionSponsors()
    {
        $sponsors = SponsorResource::find()->all();
        return ResponseHelper::sendSuccessResponse($sponsors);
    }





}
