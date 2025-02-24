<?php

namespace api\resources;

use common\models\PointsLogs;

class PointsLogsResource extends PointsLogs
{
    public function fields()
    {
        return [
            'id',
            'user_name',
            'user_mobile',
            'points_num',
            'type',
            'page_num',
            'time',
            'created_at',
        ];
    }

    public function extraFields()
    {
        return [
            'user' => function ($model) {
                return [
                    'id' => $model->user->id ?? null,
                    'name' => $model->user->username ?? null,
                    'email' => $model->user->email ?? null,
                ];
            }
        ];
    }
}