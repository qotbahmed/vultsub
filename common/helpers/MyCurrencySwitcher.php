<?php
namespace common\helpers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Cookie;

class MyCurrencySwitcher {

    public static function Convert($from='USD' , $to= "EGP" ,$amount=1,$decimal = 4){
        //incase of localhost return static value
         if(YII_ENV_DEV) return 555 ;

        $converter = new MyCurrencyConverter();
        return  number_format($converter->convert($from, $to ,$amount) , $decimal );
    }


    public static function checkCurrency($code , $amount,$formatted=true){

        if($code == Yii::$app->session->get('_currency')){
            return ;
        }else{
           $value= MyCurrencySwitcher::Convert($code,Yii::$app->session->get('_currency'),$amount , 1);
           if($formatted){
               return ' <div><span class="converted-price">'.$value.'</span><span class="currency">'.Yii::$app->session->get('_currency').'</span></div>';
           }else{
               return $value.' '.Yii::$app->session->get('_currency');

           }
        }

    }

    public static function catchCurrency()
    {
        if ( php_sapi_name() == 'cli' )
        {
            return;
        }

       // $availableCurrencies= \backend\models\Currency::find()->where(['status'=>1])->asArray()->all();
        $availableCurrencies = MyFrontCurrencies();

       // $ids = ArrayHelper::getColumn($availableCurrencies, 'currency_code');
        $ids = $availableCurrencies;

        if ( isset($_GET['_currency']) && in_array($_GET['_currency'], $ids) ) // From URL
        {
            //echo "dd";die;
            static::saveLanguage($_GET['_currency']);

        }
        elseif ( Yii::$app->session->has('_currency') ) // From session
        {
            static::saveLanguage(Yii::$app->session->get('_currency'));

        }
        elseif ( Yii::$app->response->cookies->has('_currency') ) // From cookies
        {
            static::saveLanguage(Yii::$app->response->cookies->get('_currency')->value);
        }
        else // From browser settings
        {
            static::saveLanguage('USD');
        }

    }



    protected static function saveLanguage($currency)
    {
        if ( Yii::$app->session->get('_currency') != $currency )
        {
            Yii::$app->session->set('_currency', $currency);
        }

        if ( !Yii::$app->response->cookies->get('_currency') || Yii::$app->response->cookies->get('_currency')->value != $currency )
        {
            $cookie = new Cookie([
                'name' => '_currency',
                'value' => $currency,
                'expire' => time() + (3600*24*30), // 30 days
            ]);

            Yii::$app->response->cookies->add($cookie);
        }
    }


    public static function createMulticurrencyReturnUrl($currency)
    {
        if (count($_GET) > 0)
        {
            $arr = $_GET;
            $arr['_currency']= $currency;
        }
        else
            $arr = array('_currency'=>$currency);

        if (Yii::$app->requestedRoute != Yii::$app->errorHandler->errorAction)
        {
            $arr[0] = '';
            return Url::to($arr);
        }
        else
        {
            if ( isset( $_SERVER['REQUEST_URI'], $_GET['_currency'] ) )
            {
                $url = ltrim($_SERVER['REQUEST_URI'], '/'.$_GET['_currency']);
                return '/' . $currency .'/'. $url;
            }
            else
                return Yii::$app->homeUrl;
        }
    }


}

?>