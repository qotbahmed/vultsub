<?php

namespace frontend\controllers;
use webvimark\behaviors\multilanguage\MultiLanguageHelper;
use Yii;
use yii\web\Controller;
use common\models\UserProfile;
use common\models\Academies;

/**
 * Site controller
 */
class FrontController extends Controller
{
    public $academy;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ],
            'set-locale' => [
                'class' => 'common\actions\SetLocaleAction',
                'locales' => array_keys(Yii::$app->params['availableLocales'])
            ]
        ];
    }



    public function AllTags($title = null, $url = null, $description = null, $image = null, $alt = null)
    {

        if (!$title) {
            $title = 'site';
        }

        if (!$url) {
            $url = '';
        }

        if (!$image) {
            $image = "";
        }

        if (!$alt) {
            $alt = "";
        }

        if ($description) {
            $description = strip_tags($description);
            //meta Tags
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $description,
            ]);
            \Yii::$app->view->registerMetaTag([
                'property' => 'og:description',
                'content' => $description,
            ]);
        }

        \Yii::$app->view->registerMetaTag([
            'property' => 'fb:app_id',
            'content' => env('FACEBOOK_CLIENT_ID'),
        ]);
        \Yii::$app->view->registerMetaTag([
            'property' => 'og:url',
            'content' => $url,
        ]);
        \Yii::$app->view->registerMetaTag([
            'property' => 'og:title',
            'content' => $title,
        ]);

        \Yii::$app->view->registerMetaTag([
            'property' => 'og:image',
            'content' => $image,
            'alt' => $title,
        ]);
        \Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $url,
        ]);
        \Yii::$app->view->registerMetaTag([
            'property' => 'og:type',
            'content' => "website",
        ]);
    }
    public function init()
    {
        MultiLanguageHelper::catchLanguage();
        \Yii::$app->language= 'ar';

        $this->setAcademy();

        parent::init();
    }

    private function setAcademy()
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->identity->id;
            $userProfile = UserProfile::find()->where(['user_id' => $userId])->one();
            if ($userProfile) {
                $this->academy = Academies::find()->where(['id' => $userProfile->academy_id])->one();
            }
        }
    }

}
