<?php

return [

 /*****************Sample**************************/

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'company/payment',
        'only' => ['index','view','update','create','options'],
        'extraPatterns' => [
            'GET ' => 'index',
            'GET <id>' => 'view',
            'POST ' => 'create',
            'PUT ' => 'update',
        ],
        'pluralize' => false,
    ],


];
