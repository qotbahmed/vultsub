<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\SubscriptionPlan;
use common\helpers\EmailHelper;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $password_repeat;
    public $academy_name;
    public $branches_count;
    public $phone;
    public $agree_terms;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'password', 'password_repeat', 'academy_name', 'branches_count', 'agree_terms'], 'required'],
            [['first_name', 'last_name', 'academy_name'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            [['password'], 'string', 'min' => 8],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password'],
            [['branches_count'], 'integer', 'min' => 1, 'max' => 100],
            [['phone'], 'string', 'max' => 20],
            [['agree_terms'], 'boolean'],
            [['agree_terms'], 'compare', 'compareValue' => 1, 'message' => 'You must agree to the terms and conditions.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Repeat Password',
            'academy_name' => 'Academy Name',
            'branches_count' => 'Number of Branches',
            'phone' => 'Phone Number',
            'agree_terms' => 'I agree to the Terms and Conditions',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->academy_name = $this->academy_name;
        $user->branches_count = $this->branches_count;
        $user->phone = $this->phone;
        $user->subdomain = $this->generateUniqueSubdomain($this->academy_name);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->is_trial = 1;
        $user->trial_ends_at = date('Y-m-d H:i:s', strtotime('+' . Yii::$app->params['trialDays'] . ' days'));
        $user->subscription_status = User::SUBSCRIPTION_STATUS_TRIAL;

        if ($user->save()) {
            // Send verification email
            $this->sendVerificationEmail($user);
            
            // Send welcome email
            $this->sendWelcomeEmail($user);
            
            return true;
        }

        return false;
    }

    /**
     * Generate unique subdomain
     */
    private function generateUniqueSubdomain($academyName)
    {
        $baseSubdomain = $this->slugify($academyName);
        $subdomain = $baseSubdomain;
        $counter = 1;

        while (User::find()->where(['subdomain' => $subdomain])->exists()) {
            $subdomain = $baseSubdomain . $counter;
            $counter++;
        }

        return $subdomain;
    }

    /**
     * Convert string to URL-friendly slug
     */
    private function slugify($text)
    {
        // Convert to lowercase
        $text = strtolower($text);
        
        // Replace Arabic characters with English equivalents
        $arabic = ['ا', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي'];
        $english = ['a', 'b', 't', 'th', 'j', 'h', 'kh', 'd', 'dh', 'r', 'z', 's', 'sh', 's', 'd', 't', 'z', 'a', 'gh', 'f', 'q', 'k', 'l', 'm', 'n', 'h', 'w', 'y'];
        $text = str_replace($arabic, $english, $text);
        
        // Remove special characters and replace spaces with hyphens
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');
        
        // Limit length
        return substr($text, 0, 30);
    }

    /**
     * Send verification email
     */
    private function sendVerificationEmail($user)
    {
        try {
            return Yii::$app->mailer->compose('emailVerification', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
                ->setTo($user->email)
                ->setSubject('Verify your email address - Vult SaaS')
                ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send verification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send welcome email
     */
    private function sendWelcomeEmail($user)
    {
        try {
            return Yii::$app->mailer->compose('welcome', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
                ->setTo($user->email)
                ->setSubject('Welcome to Vult SaaS - Your Academy Management Platform')
                ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
    }
}
