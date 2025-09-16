<?php

return [
    'adminEmail' => env('ADMIN_EMAIL', 'admin@vult-saas.com'),
    'supportEmail' => env('SUPPORT_EMAIL', 'support@vult-saas.com'),
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'academyDomain' => env('ACADEMY_DOMAIN', 'vult-saas.localhost'),
    'trialDays' => env('TRIAL_DAYS', 7),
    'trialNotificationDays' => env('TRIAL_NOTIFICATION_DAYS', 2),
    'stripe' => [
        'publishableKey' => env('STRIPE_PUBLISHABLE_KEY'),
        'secretKey' => env('STRIPE_SECRET_KEY'),
        'webhookSecret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'maxFileSize' => env('MAX_FILE_SIZE', 10485760), // 10MB
    'allowedExtensions' => explode(',', env('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf,doc,docx')),
];
