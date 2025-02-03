<?php

namespace api\helpers;

use Yii;

class ApiHelper
{
    public static function allowedDomains()
    {
        return [
            //'*',
            'http://127.0.0.1:3000',
            'http://localhost:3000',
            'http://localhost',
        ];
    }

}