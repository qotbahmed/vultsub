<?php

namespace api\resources;

use Yii;

class UserResource extends \common\models\User
{
    public function fields()
    {
        return [
            'id',
            'first_name' => function () {
                return $this->userProfile->firstname;
            },
            'last_name' => function () {
                return $this->userProfile->lastname;
            },
            'name' => function () {
                return $this->userProfile->firstname . ' ' . $this->userProfile->lastname;
            },
            'email',
            'mobile' => function () {
                return $this->mobile;
            },
            'status'=> function(){
                return [
                    'id' => $this->status,
                    'text' => $this->statuses()[$this->status],
                ];
            },
            'picture' => function () {
                return $this->userProfile->getAvatar();
            },
            'surah' => function () {
                return $this->userProfile->surah;
            },
            'ayah_num' => function () {
                return $this->userProfile->ayah_num;
            },
            'points_num' => function () {
                return $this->userProfile->points_num;
            },
            'page_num' => function () {
                return $this->userProfile->page_num;
            },
            'joined_at' => function () {
                return date('Y-m-d', $this->created_at);
            },



        ];
    }
}
