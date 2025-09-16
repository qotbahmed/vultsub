<?php

namespace academy\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Academies;
use common\models\UserProfile;

/**
 * TrialSignupForm is the model behind the trial signup form.
 */
class TrialSignupForm extends Model
{
    public $academy_name;
    public $manager_name;
    public $email;
    public $password;
    public $phone;
    public $branches_count;
    public $main_sport;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['academy_name', 'manager_name', 'email', 'password', 'phone', 'branches_count', 'main_sport'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'common\models\User', 'targetAttribute' => 'email'],
            ['phone', 'match', 'pattern' => '/^((009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})|(\+2|002|2)(10|11|12|15)([\d]{8})|(0)(10|11|12|15)([\d]{8}))$/', 'message' => 'رقم الهاتف غير صحيح'],
            ['phone', 'unique', 'targetClass' => 'common\models\User', 'targetAttribute' => 'mobile'],
            ['password', 'string', 'min' => 6],
            ['branches_count', 'integer', 'min' => 1],
            ['academy_name', 'string', 'max' => 100],
            ['manager_name', 'string', 'max' => 100],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'academy_name' => 'اسم الأكاديمية',
            'manager_name' => 'اسم المسؤول',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'phone' => 'رقم الهاتف',
            'branches_count' => 'عدد الفروع',
            'main_sport' => 'الرياضة الرئيسية',
        ];
    }

    /**
     * Signs up a user for trial.
     * @return bool whether the creating new account was successful
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create user
            $user = new User();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->mobile = $this->phone;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;
            $user->user_type = User::USER_TYPE_ACADEMY_MANAGER;
            $user->created_at = time();
            $user->updated_at = time();
            $user->trial_started_at = time();
            $user->trial_expires_at = time() + (Yii::$app->params['trialDays'] * 24 * 60 * 60);

            if (!$user->save()) {
                throw new \Exception('Failed to create user: ' . implode(', ', $user->getFirstErrors()));
            }

            // Create academy
            $academy = new Academies();
            $academy->title = $this->academy_name;
            $academy->contact_email = $this->email;
            $academy->contact_phone = $this->phone;
            $academy->manager_id = $user->id;
            $academy->main = 1;
            $academy->status = Academies::STATUS_ACTIVE;
            $academy->created_at = date('Y-m-d H:i:s');
            $academy->updated_at = date('Y-m-d H:i:s');
            $academy->primary_color = '#1e3c72';
            $academy->secondary_color = '#2a5298';
            $academy->accent_color = '#ff6b35';

            if (!$academy->save()) {
                throw new \Exception('Failed to create academy: ' . implode(', ', $academy->getFirstErrors()));
            }

            // Update user with academy_id
            $user->academy_id = $academy->id;
            if (!$user->save()) {
                throw new \Exception('Failed to update user with academy_id: ' . implode(', ', $user->getFirstErrors()));
            }

            // Create user profile
            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->firstname = explode(' ', $this->manager_name)[0];
            $profile->lastname = implode(' ', array_slice(explode(' ', $this->manager_name), 1));
            $profile->created_at = time();
            $profile->updated_at = time();

            if (!$profile->save()) {
                throw new \Exception('Failed to create user profile: ' . implode(', ', $profile->getFirstErrors()));
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('Trial signup failed: ' . $e->getMessage());
            $this->addError('email', 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.');
            return false;
        }
    }
}
