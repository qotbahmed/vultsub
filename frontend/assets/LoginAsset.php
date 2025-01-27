<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace frontend\assets;

use common\assets\AdminLte;
use common\assets\Html5shiv;
use yii\web\AssetBundle;
use yii\web\YiiAsset;
use rmrevin\yii\fontawesome\NpmFreeAssetBundle;

class LoginAsset extends AssetBundle
{
    /**
     * @var string
     */
   // public $sourcePath = '@backend/web/bundle';
    /**
     * @var string
     */
    public $basePath = '@webroot';
    /**
     * @var string
     */
    public $baseUrl = '@web';


    /**
     * @var array
     */
    public $css = [
        'css/login-style.css',
        'css/bootstrap.min.css',
        'fonts/icomoon/style.css',
    ];
    /**
     * @var array
     */
    public $js = [
       /* 'js/libs/chart.min.js',
        'js/app.js'*/
    ];

    public $publishOptions = [
        'only' => [
            '*.css',
            '*.js',
            '../img/*'
        ],
        "forceCopy" => YII_ENV_DEV,
    ];

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        AdminLte::class,
        Html5shiv::class,
    ];
}
