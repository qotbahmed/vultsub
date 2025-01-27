<?php

namespace api\controllers;

use api\resources\BusinessSectorResource;
use common\helpers\Helper;
use Yii;
use api\helpers\ResponseHelper;
use api\resources\CountryResource;

class LookupsController extends RestController
{

    public function actionCountry()
    {
        $query = CountryResource::find();

        $search = Yii::$app->request->get('search', null);

        if ($search) {
            $query->andFilterWhere([
                'or',
                ['like', 'name_ar', $search],
                ['like', 'name_en', $search],
                ['like', 'nationality_ar', $search],
                ['like', 'nationality_en', $search],
            ]);
        }

        $countries = $query->all();
        return ResponseHelper::sendSuccessResponse($countries);
    }

    public function actionBusinessSectors()
    {
        $query = BusinessSectorResource::find();

        $search = Yii::$app->request->get('search', null);

        if ($search) {
            $query->andFilterWhere([
                'or',
                ['like', 'name_ar', $search],
                ['like', 'name_en', $search],
            ]);
        }

        $businessSectors = $query->all();
        return ResponseHelper::sendSuccessResponse($businessSectors);
    }

    public function actionCompanySizes()
    {
        $data = Helper::companySizes();
        return ResponseHelper::sendSuccessResponse($data);
    }





}
