<?php


return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // Pages
        ['pattern' => 'about', 'route' => 'page/about'],
        ['pattern' => 'contact', 'route' => 'page/contact'],
        //['pattern' => 'page/<slug>', 'route' => 'page/view'],

    ]
];
