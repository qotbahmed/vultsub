<?php

return [

    /*****************Sample**************************/

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'parent/player',
        'only' => ['index', 'view', 'create','news','schedule','nearby-schedule', 'update', 'delete', 'options', 'player-team'],
        'extraPatterns' => [
            'GET nearby-schedule' => 'nearby-schedule',
            'GET schedule' => 'schedule',
            'GET news' => 'news',
            'GET player-team' => 'player-team',
        ],
        'pluralize' => false,
    ],

];
