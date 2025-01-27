<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class JqueryValidate
 * @package common\assets
 * @author Eugene Terentev <eugene@terentev.net>
 */
class JqueryValidate extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@npm/jquery-validation/dist';
    /**
     * @var array
     */
    public $js = [
        'jquery.validate.min.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class
    ];
}
