<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\AcademyRequest;

/**
 * RegisterForm is the model behind the registration form.
 */
class RegisterForm extends Model
{
    public $academy_name;
    public $manager_name;
    public $email;
    public $phone;
    public $city;
    public $branches_count = 1;
    public $sports = [];
    public $description;
    public $password;
    public $confirm_password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['academy_name', 'manager_name', 'email', 'phone', 'password', 'confirm_password'], 'required'],
            [['branches_count'], 'integer', 'min' => 1],
            [['sports'], 'safe'],
            [['description'], 'string'],
            [['academy_name', 'manager_name', 'city'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^[\+]?[0-9\s\-\(\)]{7,20}$/', 'message' => 'رقم الهاتف غير صحيح'],
            [['password'], 'string', 'min' => 6],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password'],
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'academy_name' => 'Academy Name',
            'manager_name' => 'Manager Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'city' => 'City',
            'branches_count' => 'Number of Branches',
            'sports' => 'Sports',
            'description' => 'Description',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
        ];
    }

    /**
     * Additional validation before save
     *
     * @return bool whether validation passed
     */
    public function validateBeforeSave()
    {
        // Check if email already exists
        if (User::find()->where(['email' => $this->email])->exists()) {
            $this->addError('email', 'البريد الإلكتروني مستخدم بالفعل');
            return false;
        }
        
        // Check if subdomain would be unique
        $subdomain = strtolower(str_replace(' ', '-', $this->academy_name)) . '-' . time();
        if (User::find()->where(['subdomain' => $subdomain])->exists()) {
            $this->addError('academy_name', 'اسم الأكاديمية مستخدم بالفعل');
            return false;
        }
        
        return true;
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }
        
        // Additional validation before save
        if (!$this->validateBeforeSave()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create academy request
            $academyRequest = new AcademyRequest();
            $academyRequest->academy_name = $this->academy_name;
            $academyRequest->manager_name = $this->manager_name;
            $academyRequest->email = $this->email;
            $academyRequest->phone = $this->phone;
            $academyRequest->city = $this->city;
            $academyRequest->branches_count = $this->branches_count;
            $academyRequest->sports = implode(',', $this->sports);
            $academyRequest->description = $this->description;
            $academyRequest->status = AcademyRequest::STATUS_PENDING;
            $academyRequest->requested_at = date('Y-m-d H:i:s');
            if (!$academyRequest->save()) {
                throw new \Exception('Failed to create academy request: ' . implode(', ', $academyRequest->getFirstErrors()));
            }

            // Create user account
            $user = new User();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->first_name = $this->manager_name;
            $user->last_name = 'Manager'; // Set a default last name
            $user->user_type = User::USER_TYPE_ACADEMY_ADMIN; // Set user_type as integer (3 for academy_admin)
            $user->subdomain = strtolower(str_replace(' ', '-', $this->academy_name)) . '-' . time();
            $user->academy_name = $this->academy_name;
            $user->phone = $this->phone;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;

            
            // Set trial period
            $user->trial_started_at = time();
            $user->trial_expires_at = time() + (7 * 24 * 60 * 60); // 7 days
    

            if (!$user->save()) {
                throw new \Exception('Failed to create user account: ' . implode(', ', $user->getFirstErrors()));
            }
            
            // Link user to academy request
            $academyRequest->user_id = $user->id;
            $academyRequest->save();

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            // Log the error for debugging
            Yii::error('Registration failed: ' . $e->getMessage(), __METHOD__);
            
            // Add specific error based on the exception
            if (strpos($e->getMessage(), 'academy request') !== false) {
                $this->addError('academy_name', 'فشل في إنشاء طلب الأكاديمية: ' . $e->getMessage());
            } elseif (strpos($e->getMessage(), 'user account') !== false) {
                $this->addError('email', 'فشل في إنشاء حساب المستخدم: ' . $e->getMessage());
            } else {
                $this->addError('email', 'حدث خطأ غير متوقع: ' . $e->getMessage());
            }
            
            return false;
        }
    }
}
