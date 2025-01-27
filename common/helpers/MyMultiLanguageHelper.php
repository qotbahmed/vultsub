<?php
namespace common\helpers;
use Yii;
use yii\helpers\Url;
use yii\web\Cookie;
use webvimark\behaviors\multilanguage\MultiLanguageHelper;


class MyMultiLanguageHelper extends  MultiLanguageHelper
{

    /**
     * catchLanguage
     *
     * Changing language depending on the $_GET['_language'] parameter
     *
     * Used in base Controller in init() function
     *
     * @stolen from http://www.yiiframework.com/wiki/294/seo-conform-multilingual-urls-language-selector-widget-i18n/
     */
    public static function catchLanguage()
    {


        $availableLanguages = array_keys(Yii::$app->params['mlConfig']['languages']);

        if ( isset($_GET['_language']) && in_array($_GET['_language'], $availableLanguages) ) // From URL
        {
            Yii::$app->language = $_GET['_language'];

            static::saveLanguage(Yii::$app->language);


        }
        elseif ( Yii::$app->session->has('_language') ) // From session
        {
            Yii::$app->language = Yii::$app->session->get('_language');

            static::saveLanguage(Yii::$app->language);

        }
        elseif ( Yii::$app->response->cookies->has('_language') ) // From cookies
        {
            Yii::$app->language = Yii::$app->response->cookies->get('_language')->value;

            static::saveLanguage(Yii::$app->language);

        }
        else // From browser settings
        {
            // Yii::$app->language = Yii::$app->request->getPreferredLanguage($availableLanguages);

           // static::saveLanguage(My_client_browser_lang());
            static::saveLanguage('ar');
        }

    }


}