<?php

namespace api\resources;


use backend\models\Settings;

class SettingsResource extends Settings
{
    public function fields()
    {
        return [
            'email',
            'points_per_second',
            'daily_points',
            'reading_points_delay',
            'max_daily_points_per_user'

        ];
    }
}
