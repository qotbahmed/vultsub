<?php

namespace common\models;

use common\models\User;

use \common\models\base\SchedulesPlayer as BaseSchedulesPlayer;

/**
 * This is the model class for table "schedules_player".
 */
class SchedulesPlayer extends BaseSchedulesPlayer
{
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return array_replace_recursive(parent::rules(),
//	    []);
//    }

    public function getPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'player_id']);
    }
	
}
