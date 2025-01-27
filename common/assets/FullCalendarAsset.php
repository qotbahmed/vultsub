<?php

namespace common\assets;

use trntv\yii\datetime\assets\MomentAsset;
use Yii;
use yii\web\AssetBundle;

class FullCalendarAsset extends AssetBundle
{
    public $sourcePath = '@backend/web/js/libs/fullcalendar-6.1.15';
    
    public $css = [
        // 'css/bs-stepper.min.css',
    ];

    public $js = [
        'dist/index.global.min.js',
        'packages/moment-timezone/index.global.min.js',
        'packages/moment/index.global.min.js',
    ];

    public $depends = [
       MomentAsset::class
    ];
}
