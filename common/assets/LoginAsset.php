<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace common\assets;

use common\assets\AdminLte;
use common\assets\Html5shiv;
use yii\web\AssetBundle;
use yii\web\YiiAsset;
use rmrevin\yii\fontawesome\NpmFreeAssetBundle;

class LoginAsset extends AssetBundle
{

    public $basePath;
    public $baseUrl;
    /**
     */
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

    public function init()
    {
        parent::init();

        $this->basePath = '@backend/web';
        $this->baseUrl = '@backendUrl';
    }

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        AdminLte::class,
        Html5shiv::class,
    ];
}
