<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\User;
use api\helpers\ImageHelper;
use common\models\UserProfile;
use api\helpers\ResponseHelper;

/**
 * Model representing  Signup Form.
 */
class UserSignup extends Model
{
    public $first_name;
    public $last_name;
    public $fullname;
    public $email;
    public $mobile;
    public $password;
//    public $password_repeat;
//    public $company_name;
//    public $company_cr;
//    public $company_cr_file;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['fullname', 'email', 'password'], 'required'],//'mobile', 'company_name', 'company_cr', 'company_cr_file'
            [['fullname', 'email'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['mobile', 'safe'],
            [['email'], 'string', 'max' => 200],
            [['fullname'], 'string', 'min' => 3, 'max' => 50],
            [
                'email', 'unique', 'targetClass' => '\common\models\User',
            ],
//            ['mobile', 'filter', 'filter' => 'trim'],
//            ['mobile', 'match', 'pattern' => '/^\+?\d{1,4}?\s?\(?\d{1,4}?\)?[-.\s]?\d{1,10}$/'],
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
            'first_name' => Yii::t('common', 'First Name'),
            'last_name' => Yii::t('common', 'Last Name'),
            'email' => Yii::t('common', 'Email'),
            'password' => Yii::t('common', 'Password'),
            'password_repeat' => Yii::t('common', 'Repeat Password'),
        ];
    }

    /**
     * Signs up the user.
     * If scenario is set to "rna" (registration needs activation), this means
     * that user need to activate his account using email confirmation method.
     *
     * @return array The saved model or null if saving fails.
     */
    public function signup()
    {
        if ($this->validate()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = new User();
                $user->username = $this->email;
                $user->email = $this->email;
                $user->mobile = $this->mobile;
                $user->setPassword($this->password);
                $user->generateAuthKey();
                $user->status = User::STATUS_NOT_ACTIVE;
                if ($user->save()) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole(User::ROLE_USER);
                    $auth->assign($role, $user->id);

                    $profile = new UserProfile();
                    $profile->firstname = $this->fullname;
                    $profile->lastname = '';
                    $profile->locale = 'en-US';
//                    if ($this->company_cr_file) {
//                        $filename = ImageHelper::Base64FileUpload($this->company_cr_file, 'company_cr_file');
//                        $profile->company_cr_file_path = 'company_cr_file/' . $filename;
//                    }
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
                //$user->addError('company_cr_file', $e->getMessage());
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
