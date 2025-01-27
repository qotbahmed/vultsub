<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use api\helpers\ImageHelper;
use common\models\UserProfile;
use api\helpers\ResponseHelper;

/**
 * Model representing  Signup Form.
 */
class ParentSignup extends Model
{
    public $firstname;
    public $lastname;
    public $academy_id;
    public $email;
    public $mobile;
    public $password;
    public $image_binary;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['firstname', 'email', 'password', 'mobile' ], 'required'],
            [['firstname','lastname','email'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 200],
            [['firstname','lastname'], 'string', 'min' => 3, 'max' => 50],
            [
                'email', 'unique', 'targetClass' => '\common\models\User',
            ],
            ['mobile', 'filter', 'filter' => 'trim'],
          //  ['mobile', 'match', 'pattern' => '/^\+?\d{1,4}?\s?\(?\d{1,4}?\)?[-.\s]?\d{1,10}$/'],
            ['mobile', 'match', 'pattern' => '/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            [['image_binary','academy_id'],'safe']
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('common', 'Email'),
            'password' => Yii::t('common', 'Password'),
            'academy_id' => Yii::t('common', 'Academy'),
        ];
    }

    /**
     * Signs up the user.
     * If scenario is set to "rna" (registration needs activation), this means
     * that user need to activate his account using email confirmation method.
     *
     * @return User|null The saved model or null if saving fails.
     */
    public function signup()
    {
        if ($this->validate()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = new User();
                $user->username = $this->email;
                $user->mobile = $this->mobile;
                $user->email = $this->email;
                $user->user_type = User::USER_TYPE_PARENT;
                $user->setPassword($this->password);
                $user->generateAuthKey();
                $user->status = User::STATUS_ACTIVE;
                if ($user->save()) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole(User::ROLE_USER);
                    $auth->assign($role, $user->id);

                    $profile = new UserProfile();
                    $profile->firstname = $this->firstname;
                    $profile->academy_id = $this->academy_id;
                    $profile->lastname = $this->lastname;
                    $profile->locale = 'en-US';
                    $user->link('userProfile', $profile);
                    $transaction->commit();

                    return [
                        'status' => true,
                        'user' => $user,
                    ];
                }

                return [
                    'status' => false,
                    'errors' => $user->getFirstErrors(),
                ];
            } catch (\Exception $e) {
                // If an exception is thrown, roll back the transaction
                $transaction->rollBack();
                return [
                    'status' => false,
                    'errors' => $user->getFirstErrors(),
                ];
            }
        }
        return [
            'status' => false,
            'errors' => $this->getFirstErrors(),
        ];
    }
}
