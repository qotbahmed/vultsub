<?php

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY', 'frontend-cookie-key'),
        ],
    ],
];
