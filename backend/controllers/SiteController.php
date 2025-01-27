<?php

namespace backend\controllers;

use backend\models\Contact;
use backend\models\ContactUs;
use common\helpers\EmailHelper;
use Yii;
use backend\models\Settings;
use common\models\User;
use common\models\Employees;
use common\models\Departments;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends BackendController
{
    public $from = null;
    public $to = null;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = Yii::$app->user->isGuest || !Yii::$app->user->can('loginToBackend') ? 'base' : 'common';
        return parent::beforeAction($action);
    }


    public function actionIndex(){

        if( \Yii::$app->user->can('manager') or  \Yii::$app->user->can('administrator')) {
            $view = 'index';
        } else if( \Yii::$app->user->can('customRole')) {
            $view = 'custom';
        }else{
            //logout
            Yii::$app->user->logout();
            return $this->redirect(['/sign-in/login']);
        }

        return $this->render('index', [
            'employeeCount' => 20,
            'departmentCount' => 10,
            'femaleEmployeeCount' => 10,
            'maleEmployeeCount' => 10,
        ]);

       // return $this->render($view);

    }
    public function actionDateRange()
    {

        if (Yii::$app->request->isPost) {
            $this->from = Yii::$app->request->post('from');
            $this->to = Yii::$app->request->post('to');

        }


//        $response = ['from' => $this->from, 'to' => $this->to];
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        return $response;

//        return $this->redirect(['index', 'from' => $this->from, 'to' => $this->to]);

        return $this->render('index', ['from' => $this->from, 'to' => $this->to]);

    }
    public function actionTest()
    {

       return   EmailHelper::instance()->SendWelcome('mohamed.amer2050@gmail.com');

    }

    public function actionTestDistance()
    {
        $lat = 24.726651036820087;
        $lng = 46.68339695781469;
        $settings = Settings::findOne(1);
        $range = $settings->distance_range;// km
        // earth's radius in km = ~6371
        $radius = 6371;
        $maxlat = $lat + rad2deg($range / $radius);
        $minlat = $lat - rad2deg($range / $radius);
        $maxlng = $lng + rad2deg($range / $radius / cos(deg2rad($lat)));
        $minlng = $lng - rad2deg($range / $radius / cos(deg2rad($lat)));

        $query = User::find();
        $query->where(['between', 'lat', $minlat, $maxlat])->andWhere(['between', 'lng', $minlng, $maxlng]);
        $query->andWhere(['user.status' => User::STATUS_ACTIVE]);

        $query->leftJoin('user_profile', 'user_profile.user_id = user.id');
        $query->select(['user.*',"ROUND((((acos(sin((".$lat."*pi()/180)) * sin((`lat`*pi()/180))+cos((".$lat."*pi()/180)) * cos((`lat`*pi()/180)) * cos(((".$lng."- `lng`)*pi()/180))))*180/pi())*60*1.1515*1.609344),2) as distance"]);//distance in km
        $query->having('distance <='.$range);
        $query->orderBy('distance');
        $specifiedNanies = $query->all();

        var_dump($specifiedNanies);
    }

}
