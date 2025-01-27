<?php

namespace common\helpers;

 class TimeHelper{

     public static function TimeSince ($timestamp,$week=false){

         $periods = array(
             "second"  => "ثانية",
             "seconds" => "ثواني",
             "minute"  => "دقيقة",
             "minutes" => "دقائق",
             "hour"    => "ساعة",
             "hours"   => "ساعات",
             "day"     => "يوم",
             "days"    => "أيام",
             "month"   => "شهر",
             "months"  => "شهور",
         );

         $difference = (int) abs(time() - $timestamp);

         $plural = array(3,4,5,6,7,8,9,10);

         $readable_date = "";

         if ($difference < 60) // less than a minute
         {
             $readable_date .= $difference . " ";
             if (in_array($difference, $plural)) {
                 $readable_date .= $periods["seconds"];
             } else {
                 $readable_date .= $periods["second"];
             }
         }
         elseif ($difference < (60*60)) // less than an hour
         {
             $diff = (int) ($difference / 60);
             $readable_date .= $diff . " ";
             if (in_array($diff, $plural)) {
                 $readable_date .= $periods["minutes"];
             } else {
                 $readable_date .= $periods["minute"];
             }
         }
         elseif ($difference < (24*60*60)) // less than a day
         {
             $diff = (int) ($difference / (60*60));
             $readable_date .= $diff . " ";
             if (in_array($diff, $plural)) {
                 $readable_date .= $periods["hours"];
             } else {
                 $readable_date .= $periods["hour"];
             }
         }
         elseif ($difference < (7*24*60*60)) // less than a week
         {
             $diff = (int) ($difference / (24*60*60));
             $readable_date .= $diff . " ";
             if (in_array($diff, $plural)) {
                 $readable_date .= $periods["days"];
             } else {
                 $readable_date .= $periods["day"];
             }
             if($week) return $readable_date ;
         }

         elseif ($difference < (30*24*60*60)) // less than a month
         {
             $diff = (int) ($difference / (24*60*60));
             $readable_date .= $diff . " ";
             if (in_array($diff, $plural)) {
                 $readable_date .= $periods["days"];
             } else {
                 $readable_date .= $periods["day"];
             }
         }
         elseif ($difference < (365*24*60*60)) // less than a year
         {
             $diff = (int) ($difference / (30*24*60*60));
             $readable_date .= $diff . " ";

             if (in_array($diff, $plural)) {
                 $readable_date .= $periods["months"];
             } else {
                 $readable_date .= $periods["month"];
             }
         }
         else
         {
             $readable_date = date("d-m-Y", $timestamp);
         }

         return $readable_date;
     }

     public static  function ArabicMonths(){

         return  [
             '01'=>'يناير',
             '02'=>'فبراير',
             '03'=>'مارس',
             '04'=>'ابريل',
             '05'=>'مايو',
             '06'=>'يونيو',
             '07'=>'يوليو',
             '08'=>'أغسطس',
             '09'=>'سبتمبر',
             '10'=>'اكتوبر',
             '11'=>'نوفمبر',
             '12'=>'ديسمبر',
         ];
     }


     public static function  MessageTime($date_time,$timestamp=false){

         if($timestamp){
             $timestamp = $date_time;
         }else{
             $timestamp = strtotime($date_time);
         }

         $difference = (int) abs(time() - $timestamp);

         if ($difference < 7*24*60*60){// less than a week
                return 'منذ '. TimeHelper::TimeSince($timestamp,true);
         }



         if($timestamp) {
             $datetime = new \DateTime();
             $datetime->format('U = Y-m-d H:i:s');
             $datetime->setTimestamp($timestamp);
         }else{
             $datetime = new \DateTime($date_time);
             $day=  $datetime->format('d');
         }


         $year=  $datetime->format('Y');
         $day=  $datetime->format('d');
         $month= TimeHelper::ArabicMonths()[$datetime->format('m')];

         if($year < date('Y')){
             $year_section =  $year.'-' ;
         }else{
             $year_section ='';
         }

        return  $year_section.$day.'- '.$month ;
     }
     public static function CurrentTstmp(){

         $datetime = new \DateTime("now");
         return  $datetime->getTimeStamp();
     }


     public static function arabicDate($date){
        // PHP Arabic Date

        $months = array(
            "Jan" => "يناير",
            "Feb" => "فبراير",
            "Mar" => "مارس",
            "Apr" => "أبريل",
            "May" => "مايو",
            "Jun" => "يونيو",
            "Jul" => "يوليو",
            "Aug" => "أغسطس",
            "Sep" => "سبتمبر",
            "Oct" => "أكتوبر",
            "Nov" => "نوفمبر",
            "Dec" => "ديسمبر"
        );

         $sentDate = new \DateTime($date);

         $ymdNow = $sentDate->format('y-m-d');


        $your_date =  $ymdNow; // $date; //the date to convers (y-m-d)

        $en_month = date("M", strtotime($your_date));

        foreach ($months as $en => $ar) {
            if ($en == $en_month) {
                $ar_month = $ar;
            }
        }

        $find = array (

            "Sat",
            "Sun",
            "Mon",
            "Tue",
            "Wed" ,
            "Thu",
            "Fri"

        );

        $replace = array (

            "السبت",
            "الأحد",
            "الإثنين",
            "الثلاثاء",
            "الأربعاء",
            "الخميس",
            "الجمعة"

        );

        $ar_day_format = date('D',strtotime($your_date)); // The Current Day

        $ar_day = str_replace($find, $replace, $ar_day_format);


        header('Content-Type: text/html; charset=utf-8');
        $standard = array("0","1","2","3","4","5","6","7","8","9");
        $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
        $current_date = $ar_day.' '.date('d',strtotime($your_date)).' / '.$ar_month.' / '.date('Y');     //الخميس ٢٨ / مايو / ٢٠١٥
         $modifiedFormat= date('d',strtotime($your_date)).' '.$ar_month;

        $arabic_date = str_replace($standard , $eastern_arabic_symbols , $modifiedFormat);

        // Echo Out the Date
        return  $arabic_date;

     }

    public static function ampmto24($time){
        $dateTime = new \DateTime($time);
        $formattedTime = $dateTime->format('H:i:s');
        return $formattedTime;
    }

     public static function getDateOfDayOfCurrentWeek($day_no)
     {  // Get the current day of the week (1 = Monday, 7 = Sunday)
         $currentDayOfWeek = date('N');
         // Calculate the difference between the current day and the desired day
         $dayDifference = $day_no - $currentDayOfWeek;
         // Get the date of the desired day of the current week in the desired format (Y-m-d H:i:s)
         $desiredDate = (new \DateTime())->modify("$dayDifference days")->setTime(0, 0, 0)->format('Y-m-d H:i:s');
         // Return the formatted date
         return $desiredDate;
     }

     public static function getDateOfDayOfCurrentWeekNoTime($day_no)
     {  // Get the current day of the week (1 = Monday, 7 = Sunday)
         $currentDayOfWeek = date('N');
         // Calculate the difference between the current day and the desired day
         $dayDifference = $day_no - $currentDayOfWeek;
         // Get the date of the desired day of the current week in the desired format (Y-m-d H:i:s)
         $desiredDate = (new \DateTime())->modify("$dayDifference days")->format('Y-m-d');
         // Return the formatted date
         return $desiredDate;
     }

     public static function x_week_range($date) {
         $ts = strtotime($date);
         $start = (date('w', $ts) == 0) ? $ts : strtotime('last saturday', $ts);
         return array(date('Y-m-d', $start),
             date('Y-m-d', strtotime('next friday', $start)));
     }

     public static function getArabicDayName($date) {
         // Convert the date to a timestamp
         $timestamp = strtotime($date);

         // Get the numeric representation of the day of the week (0 = Sunday, 6 = Saturday)
         $dayOfWeek = date('w', $timestamp);

         // Arabic day names
         $arabicDays = [
             'الأحد',    // Sunday
             'الإثنين',  // Monday
             'الثلاثاء', // Tuesday
             'الأربعاء', // Wednesday
             'الخميس',   // Thursday
             'الجمعة',   // Friday
             'السبت'     // Saturday
         ];

         // Return the Arabic day name
         return $arabicDays[$dayOfWeek];
     }

 }
?>
