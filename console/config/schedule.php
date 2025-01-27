<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

// Place here all of your cron jobs

// This command will execute ls command every five minutes
//$schedule->exec('ls')->everyFiveMinutes();

// This command will execute migration command of your application every hour
//$schedule->command('migrate')->hourly();

// This command will call callback function every day at 10:00
//$schedule->call(function(\yii\console\Application $app) {
//    // Some code here...
//})->dailyAt('10:00');


/*
Next your should add the following command to your crontab:

* * * * * php /path/to/yii yii schedule/run --scheduleFile=@console/config/schedule.php 1>> /dev/null 2>&1
*/
// $schedule->exec('php  /var/www/html/nany/console/yii  customer-requests/category')->everyMinute();
$schedule->exec('php  /var/www/html/nany/console/yii  customer-requests/notifications')->everyMinute();
