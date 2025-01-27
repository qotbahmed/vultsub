<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class JqueryValidate
 * @package common\assets
 * @author Eugene Terentev <eugene@terentev.net>
 */
class SweetAlertAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@npm/sweetalert2/dist';
    /**
     * @var array
     */
    public $css = [
        // 'sweetalert2.min.css'
    ];
    public $js = [
        'sweetalert2.min.js'
    ];
    /**
     * @var array
     */
    public $depends = [
       
    ];
}
