<?php

return [
    'adminEmail' => env('ADMIN_EMAIL', 'admin@vult-saas.com'),
    'supportEmail' => env('SUPPORT_EMAIL', 'support@vult-saas.com'),
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'academyDomain' => 'vult-saas.localhost',
    'trialDays' => env('TRIAL_DAYS', 7),
    'trialNotificationDays' => env('TRIAL_NOTIFICATION_DAYS', 2),
];
