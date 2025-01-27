<?php

namespace common\assets;

use Yii;
use yii\web\AssetBundle;

class StepperAsset extends AssetBundle
{
    public $sourcePath = '@npm/bs-stepper/dist';
    
    public $css = [
        // 'css/bs-stepper.min.css',
    ];

    public $js = [
        'js/bs-stepper.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
