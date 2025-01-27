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
            'add_first_contact' => function () {
                return $this->add_first_contact;
            },
            'complete_company_info' => function () {
                return $this->complete_company_info;
            },
            'identity_verified' => function () {
                return $this->identity_verified;
            },
            'joined_at' => function () {
                return date('Y-m-d', $this->created_at);
            },

            'account_type',

            'company_profile' => function () {
                return $this->companyProfile;
            },
        ];
    }
}
