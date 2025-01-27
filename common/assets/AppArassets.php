<?php

namespace common\assets;

use kartik\grid\GridView;
use Yii;
use yii\web\AssetBundle;
use yii\web\YiiAsset;


class AppArassets extends AssetBundle
{
    public $basePath;
    public $baseUrl;
    /**
     * @var array
     */
    public $css = [
        // 'css/bootstrap-rtl.css',
        // 'css/adminlte.css',
        // 'css/custom.css',
        'css/iconsax-css/isax.css',
        'css/style-ar.css',
    ];
    /**
     * @var array
     */
    public $js = [
        // 'js/libs/chart.min.js',
        'js/app.js'
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
