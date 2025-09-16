<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;
use common\models\SubscriptionPlan;
use common\helpers\NotificationHelper;

/**
 * Data management controller for SaaS platform
 */
class DataController extends Controller
{
    /**
     * Seed default subscription plans
     */
    public function actionSeedPlans()
    {
        $plans = [
            [
                'name' => 'Starter',
                'description' => 'Perfect for small academies just getting started',
                'branches_limit' => 2,
                'students_limit' => 100,
                'storage_limit_mb' => 1024,
                'price_monthly' => 29.00,
                'price_yearly' => 290.00,
                'sort_order' => 1,
                'features' => json_encode([
                    'Student Management',
                    'Basic Scheduling',
                    'Email Support',
                    'Basic Reporting',
                    'Mobile App Access'
                ])
            ],
            [
                'name' => 'Professional',
                'description' => 'Ideal for growing academies with multiple branches',
                'branches_limit' => 5,
                'students_limit' => 500,
                'storage_limit_mb' => 5120,
                'price_monthly' => 79.00,
                'price_yearly' => 790.00,
                'sort_order' => 2,
                'features' => json_encode([
                    'Everything in Starter',
                    'Advanced Scheduling',
                    'Priority Support',
                    'Advanced Reporting',
                    'Custom Branding',
                    'API Access',
                    'Multi-branch Management'
                ])
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Complete solution for large academies and franchises',
                'branches_limit' => 999,
                'students_limit' => 9999,
                'storage_limit_mb' => 51200,
                'price_monthly' => 199.00,
                'price_yearly' => 1990.00,
                'sort_order' => 3,
                'features' => json_encode([
                    'Everything in Professional',
                    'Unlimited Branches',
                    'Unlimited Students',
                    'Custom Integrations',
                    'Dedicated Support',
                    'Advanced Analytics',
                    'White-label Solution',
                    'Custom Development'
                ])
            ]
        ];

        foreach ($plans as $planData) {
            $plan = new SubscriptionPlan();
            $plan->setAttributes($planData);
            if ($plan->save()) {
                $this->stdout("Created plan: {$plan->name}\n");
            } else {
                $this->stderr("Failed to create plan: {$plan->name}\n");
                $this->stderr(print_r($plan->errors, true) . "\n");
            }
        }

        $this->stdout("Plan seeding completed.\n");
    }

    /**
     * Reset trial data (for demo purposes)
     */
    public function actionResetTrialData()
    {
        // Reset all trial users to fresh trial state
        $trialUsers = User::find()
            ->where(['is_trial' => 1])
            ->all();

        foreach ($trialUsers as $user) {
            $user->trial_ends_at = date('Y-m-d H:i:s', strtotime('+' . Yii::$app->params['trialDays'] . ' days'));
            $user->subscription_status = User::SUBSCRIPTION_STATUS_TRIAL;
            $user->is_trial = 1;
            $user->save(false);
            
            $this->stdout("Reset trial for user: {$user->email}\n");
        }

        $this->stdout("Trial data reset completed.\n");
    }

    /**
     * Create demo academy data
     */
    public function actionCreateDemoData()
    {
        // Create demo user
        $demoUser = new User();
        $demoUser->first_name = 'Demo';
        $demoUser->last_name = 'Academy';
        $demoUser->email = 'demo@vult-saas.com';
        $demoUser->academy_name = 'Demo Sports Academy';
        $demoUser->subdomain = 'demo-academy';
        $demoUser->branches_count = 2;
        $demoUser->phone = '+1234567890';
        $demoUser->setPassword('demo123456');
        $demoUser->generateAuthKey();
        $demoUser->is_trial = 1;
        $demoUser->trial_ends_at = date('Y-m-d H:i:s', strtotime('+7 days'));
        $demoUser->subscription_status = User::SUBSCRIPTION_STATUS_TRIAL;
        $demoUser->email_verified = 1;
        $demoUser->email_verified_at = date('Y-m-d H:i:s');

        if ($demoUser->save()) {
            $this->stdout("Created demo user: {$demoUser->email}\n");
            $this->stdout("Login credentials: demo@vult-saas.com / demo123456\n");
        } else {
            $this->stderr("Failed to create demo user\n");
            $this->stderr(print_r($demoUser->errors, true) . "\n");
        }
    }

    /**
     * Send trial expiration notifications
     */
    public function actionSendTrialNotifications()
    {
        $this->stdout("Checking trial expirations...\n");
        
        $trialUsers = User::find()
            ->where(['is_trial' => 1])
            ->andWhere(['not', ['trial_ends_at' => null]])
            ->all();

        foreach ($trialUsers as $user) {
            $trialEndsAt = strtotime($user->trial_ends_at);
            $now = time();
            $daysRemaining = ceil(($trialEndsAt - $now) / (24 * 60 * 60));

            if ($daysRemaining <= Yii::$app->params['trialNotificationDays'] && $daysRemaining >= 0) {
                if (NotificationHelper::sendTrialExpirationNotification($user, $daysRemaining)) {
                    $this->stdout("Sent trial notification to: {$user->email} ({$daysRemaining} days remaining)\n");
                } else {
                    $this->stderr("Failed to send notification to: {$user->email}\n");
                }
            }
        }

        $this->stdout("Trial notification check completed.\n");
    }

    /**
     * Clean up expired trial accounts
     */
    public function actionCleanupExpiredTrials()
    {
        $this->stdout("Cleaning up expired trial accounts...\n");
        
        $expiredUsers = User::find()
            ->where(['is_trial' => 1])
            ->andWhere(['<', 'trial_ends_at', date('Y-m-d H:i:s')])
            ->all();

        foreach ($expiredUsers as $user) {
            // Send final expiration notification
            NotificationHelper::sendTrialExpirationNotification($user, 0);
            
            // Update user status
            $user->subscription_status = User::SUBSCRIPTION_STATUS_EXPIRED;
            $user->save(false);
            
            $this->stdout("Expired trial for user: {$user->email}\n");
        }

        $this->stdout("Expired trial cleanup completed.\n");
    }

    /**
     * Generate sample data for testing
     */
    public function actionGenerateSampleData()
    {
        $this->stdout("Generating sample data...\n");
        
        // Create multiple demo users with different trial states
        $sampleUsers = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john@example.com',
                'academy_name' => 'Elite Sports Academy',
                'subdomain' => 'elite-sports',
                'branches_count' => 1,
                'trial_ends_at' => date('Y-m-d H:i:s', strtotime('+3 days')),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah@example.com',
                'academy_name' => 'Future Champions',
                'subdomain' => 'future-champions',
                'branches_count' => 3,
                'trial_ends_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
            ],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Al-Rashid',
                'email' => 'ahmed@example.com',
                'academy_name' => 'نادي النجوم الرياضي',
                'subdomain' => 'stars-club',
                'branches_count' => 2,
                'trial_ends_at' => date('Y-m-d H:i:s', strtotime('-1 day')), // Expired
            ],
        ];

        foreach ($sampleUsers as $userData) {
            $user = new User();
            $user->setAttributes($userData);
            $user->setPassword('password123');
            $user->generateAuthKey();
            $user->is_trial = 1;
            $user->subscription_status = User::SUBSCRIPTION_STATUS_TRIAL;
            $user->email_verified = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');

            if ($user->save()) {
                $this->stdout("Created sample user: {$user->email}\n");
            } else {
                $this->stderr("Failed to create sample user: {$user->email}\n");
            }
        }

        $this->stdout("Sample data generation completed.\n");
    }
}
