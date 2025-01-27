<?php
/**
 * Yii2 Shortcuts
 * @author Eugene Terentev <eugene@terentev.net>
 * @author Victor Gonzalez <victor@vgr.cl>
 * -----
 * This file is just an example and a place where you can add your own shortcuts,
 * it doesn't pretend to be a full list of available possibilities
 * -----
 */

 use yii\helpers\ArrayHelper;
 use yii\helpers\Html;
 


/**
 * @return int|string
 */
function getMyId()
{
    return Yii::$app->user->getId();
}

/**
 * @param string $view
 * @param array $params
 * @return string
 */
function render($view, $params = [])
{
    return Yii::$app->controller->render($view, $params);
}

/**
 * @param $url
 * @param int $statusCode
 * @return \yii\web\Response
 */
function redirect($url, $statusCode = 302)
{
    return Yii::$app->controller->redirect($url, $statusCode);
}

/**
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null)
{
    // getenv is disabled when using createImmutable with Dotenv class
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    } elseif (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }

    return $default;
}

/**
 * Renders any data provider summary text.
 *
 * @param \yii\data\DataProviderInterface $dataProvider
 * @param array $options the HTML attributes for the container tag of the summary text
 * @return string the HTML summary text
 */
function getDataProviderSummary($dataProvider, $options = [])
{
    $count = $dataProvider->getCount();
    if ($count <= 0) {
        return '';
    }
    $tag = ArrayHelper::remove($options, 'tag', 'div');
    if (($pagination = $dataProvider->getPagination()) !== false) {
        $totalCount = $dataProvider->getTotalCount();
        $begin = $pagination->getPage() * $pagination->pageSize + 1;
        $end = $begin + $count - 1;
        if ($begin > $end) {
            $begin = $end;
        }
        $page = $pagination->getPage() + 1;
        $pageCount = $pagination->pageCount;
        return Html::tag($tag, Yii::t('yii', 'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}.', [
                'begin' => $begin,
                'end' => $end,
                'count' => $count,
                'totalCount' => $totalCount,
                'page' => $page,
                'pageCount' => $pageCount,
            ]), $options);
    } else {
        $begin = $page = $pageCount = 1;
        $end = $totalCount = $count;
        return Html::tag($tag, Yii::t('yii', 'Total <b>{count, number}</b> {count, plural, one{item} other{items}}.', [
            'begin' => $begin,
            'end' => $end,
            'count' => $count,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageCount' => $pageCount,
        ]), $options);
    }
}

 function statuses()
{
    return [
        1 => Yii::t('backend', 'Active'),
        0 => Yii::t('backend', 'Not Active'),
       // 2 => Yii::t('common', 'Deleted')
    ];
}

function QuestionsStatuses()
{
    return [
        0 => Yii::t('backend', 'Not Active'),
        1 => Yii::t('backend', 'Active'),
        2 => Yii::t('backend', 'Completed'),
        // 2 => Yii::t('common', 'Deleted')
    ];
}

function CompareToCurrentDatetime($dateTime){
    $currentDateTime = new DateTime();  // Get the current date and time
    $targetDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);  // Set the target date and time

    if ($targetDateTime  > $currentDateTime  ) {
        return true ;
    } else {
        return false ;
    }
}

//conver number to english

// function myConvertEnNumbers($input)
// {
//     $unicode = array('۰', '۱', '۲', '۳', '٤', '٥', '٦', '۷', '۸', '۹');
//     $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

//     $string = str_replace($unicode, $english, $input);

//     return $string;
// }
function myConvertNumbers($string)
{
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string = str_replace($persianDecimal, $newNumbers, $string);
    $string = str_replace($arabicDecimal, $newNumbers, $string);
    $string = str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

function myClearPhone($phone)
{
    //check all is english numbers
    $phone = myConvertEnNumbers($phone);
    //remove country code
    $phone = str_replace("+966", "", $phone);
    $phone = str_replace("00966", "", $phone);
    $phone = str_replace("966", "", $phone);
    $phone = intval($phone);

    return $phone;
}
function My_client_browser_lang( $default= "en" ){
    if( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ){
        $langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
        foreach ( $langs as $value) {
            $getlang = substr( $value, 0,2 );
            return $getlang == 'ar' ? 'ar':'en';
        }
    }
//Return default.
    return $default;
}



function myPreparePhone($phone){
    $phone =  '966'. myClearPhone($phone);
    return $phone;
}



// handling uploding  to server

function myFolder($sub=''){
    return Yii::$app->user->id.DIRECTORY_SEPARATOR.date('Ymd').DIRECTORY_SEPARATOR.$sub;
}

function myFilterImagePath($path){
    $arr= explode(DIRECTORY_SEPARATOR ,$path);
    return  end($arr);
}

function myFileThumb($path){
    $arr= explode(DIRECTORY_SEPARATOR,$path);

    $file_name= end($arr);

    $new_path= str_replace($file_name , '',$path);

    return    $new_path .'thumb_'. $file_name ;
}

function myFileOrigin($path){
    $arr= explode(DIRECTORY_SEPARATOR,$path);
    $file_name= end($arr);
    array_pop($arr);
    $path= implode($arr,DIRECTORY_SEPARATOR);
    $originImage= $path.DIRECTORY_SEPARATOR.'large_'.$file_name ;

    return $originImage;
}

function myMoveOriginImage($path){
    $storage_path= Yii::getAlias('@storage'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR);

    $arr= explode(DIRECTORY_SEPARATOR,$path);

    $file_name= end($arr);
    array_pop($arr);
    array_pop($arr);
    $path= implode($arr,DIRECTORY_SEPARATOR);

    $originPath= $storage_path.$path.DIRECTORY_SEPARATOR.'origin_'.$file_name ;

    if (file_exists($originPath)) {
        $newPath=   $storage_path.$path.DIRECTORY_SEPARATOR.'1'.DIRECTORY_SEPARATOR.'large_'.$file_name ;
        rename($originPath,$newPath);
    }

    //move origin image to viewable folder
//     $str= 'mv  '.$originPath .' '. $newPath;
//      exec($str);
//     die;

    return true;
}


function myCheckOriginImage($path){

    $storage_path= Yii::getAlias('@storage'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR);
    $arr= explode(DIRECTORY_SEPARATOR,$path);
    $file_name= end($arr);
    array_pop($arr);
    array_pop($arr);
    $path= implode($arr,DIRECTORY_SEPARATOR);

    $originPath= $storage_path.$path.DIRECTORY_SEPARATOR.'1'.DIRECTORY_SEPARATOR. 'large_'.$file_name ;


    if (file_exists($originPath)) {
        return $originPath;
    }else{
        return null;
    }

}
function MyTimeSinceAgo($timestamp, $week = false)
{

//    $periods = array(
//        "second" => "ثانية",
//        "seconds" => "ثواني",
//        "minute" => "دقيقة",
//        "minutes" => "دقائق",
//        "hour" => "ساعة",
//        "hours" => "ساعات",
//        "day" => "يوم",
//        "days" => "أيام",
//        "month" => "شهر",
//        "months" => "شهور",
//    );
    $periods = array(
        "second" => "second",
        "seconds" => "seconds",
        "minute" => "minute",
        "minutes" => "minutes",
        "hour" => "hour",
        "hours" => "hours",
        "day" => "day",
        "days" => "days",
        "month" => "month",
        "months" => "months",
    );

    $difference = (int) abs(time() - $timestamp);

    $plural = array(3, 4, 5, 6, 7, 8, 9, 10);

    $readable_date = "";

    if ($difference < 60) // less than a minute
    {
        $readable_date .= $difference . " ";
        if (in_array($difference, $plural)) {
            $readable_date .= $periods["seconds"];
        } else {
            $readable_date .= $periods["second"];
        }
    } elseif ($difference < (60 * 60)) // less than an hour
    {
        $diff = (int) ($difference / 60);
        $readable_date .= $diff . " ";
        if (in_array($diff, $plural)) {
            $readable_date .= $periods["minutes"];
        } else {
            $readable_date .= $periods["minute"];
        }
    } elseif ($difference < (24 * 60 * 60)) // less than a day
    {
        $diff = (int) ($difference / (60 * 60));
        $readable_date .= $diff . " ";
        if (in_array($diff, $plural)) {
            $readable_date .= $periods["hours"];
        } else {
            $readable_date .= $periods["hour"];
        }
    } elseif ($difference < (7 * 24 * 60 * 60)) // less than a week
    {
        $diff = (int) ($difference / (24 * 60 * 60));
        $readable_date .= $diff . " ";
        if (in_array($diff, $plural)) {
            $readable_date .= $periods["days"];
        } else {
            $readable_date .= $periods["day"];
        }
        if ($week) {
            return $readable_date;
        }
    } elseif ($difference < (30 * 24 * 60 * 60)) // less than a month
    {
        $diff = (int) ($difference / (24 * 60 * 60));
        $readable_date .= $diff . " ";
        if (in_array($diff, $plural)) {
            $readable_date .= $periods["days"];
        } else {
            $readable_date .= $periods["day"];
        }
    } elseif ($difference < (365 * 24 * 60 * 60)) // less than a year
    {
        $diff = (int) ($difference / (30 * 24 * 60 * 60));
        $readable_date .= $diff . " ";

        if (in_array($diff, $plural)) {
            $readable_date .= $periods["months"];
        } else {
            $readable_date .= $periods["month"];
        }
    } else {
        $readable_date = date("d-m-Y", $timestamp);
    }

    return $readable_date;
}
function getDayName($dayId) {
    $days = [
        1 => Yii::t('common', 'Monday'),
        2 => Yii::t('common', 'Tuesday'),
        3 => Yii::t('common', 'Wednesday'),
        4 => Yii::t('common', 'Thursday'),
        5 => Yii::t('common', 'Friday'),
        6 => Yii::t('common', 'Saturday'),
        7 => Yii::t('common', 'Sunday'),
    ];

    return $days[$dayId] ?? Yii::t('common', 'Unknown');
}

