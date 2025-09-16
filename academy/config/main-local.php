<?php

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => env('ACADEMY_COOKIE_VALIDATION_KEY', 'academy-cookie-key'),
        ],
    ],
];
