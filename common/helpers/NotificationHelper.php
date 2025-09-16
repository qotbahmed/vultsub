<?php

namespace common\helpers;

use Yii;
use common\models\User;
use common\models\Subscription;

class NotificationHelper
{
    /**
     * Send trial expiration notification
     */
    public static function sendTrialExpirationNotification($user, $daysRemaining)
    {
        try {
            $subject = $daysRemaining > 0 ? 
                "Your trial expires in {$daysRemaining} days" : 
                "Your trial has expired";
                
            $template = $daysRemaining > 0 ? 'trialExpiring' : 'trialExpired';
            
            return Yii::$app->mailer->compose($template, [
                'user' => $user,
                'daysRemaining' => $daysRemaining,
                'academyUrl' => $user->getAcademyUrl(),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject($subject)
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send trial expiration notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send subscription renewal notification
     */
    public static function sendSubscriptionRenewalNotification($user, $subscription)
    {
        try {
            $daysUntilRenewal = $subscription->getDaysUntilRenewal();
            
            return Yii::$app->mailer->compose('subscriptionRenewal', [
                'user' => $user,
                'subscription' => $subscription,
                'daysUntilRenewal' => $daysUntilRenewal,
                'academyUrl' => $user->getAcademyUrl(),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject("Your subscription will renew in {$daysUntilRenewal} days")
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send subscription renewal notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment failed notification
     */
    public static function sendPaymentFailedNotification($user, $subscription)
    {
        try {
            return Yii::$app->mailer->compose('paymentFailed', [
                'user' => $user,
                'subscription' => $subscription,
                'academyUrl' => $user->getAcademyUrl(),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject('Payment Failed - Action Required')
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send payment failed notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send subscription activated notification
     */
    public static function sendSubscriptionActivatedNotification($user, $subscription)
    {
        try {
            return Yii::$app->mailer->compose('subscriptionActivated', [
                'user' => $user,
                'subscription' => $subscription,
                'academyUrl' => $user->getAcademyUrl(),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject('Your subscription is now active!')
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send subscription activated notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send subscription cancelled notification
     */
    public static function sendSubscriptionCancelledNotification($user, $subscription)
    {
        try {
            return Yii::$app->mailer->compose('subscriptionCancelled', [
                'user' => $user,
                'subscription' => $subscription,
                'academyUrl' => $user->getAcademyUrl(),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject('Your subscription has been cancelled')
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send subscription cancelled notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check and send trial expiration notifications
     */
    public static function checkTrialExpirations()
    {
        $trialUsers = User::find()
            ->where(['is_trial' => 1])
            ->andWhere(['not', ['trial_ends_at' => null]])
            ->all();

        foreach ($trialUsers as $user) {
            $trialEndsAt = strtotime($user->trial_ends_at);
            $now = time();
            $daysRemaining = ceil(($trialEndsAt - $now) / (24 * 60 * 60));

            // Send notification if trial expires in 2 days or has expired
            if ($daysRemaining <= Yii::$app->params['trialNotificationDays'] && $daysRemaining >= 0) {
                self::sendTrialExpirationNotification($user, $daysRemaining);
            }
        }
    }

    /**
     * Check and send subscription renewal notifications
     */
    public static function checkSubscriptionRenewals()
    {
        $activeSubscriptions = Subscription::find()
            ->where(['status' => Subscription::STATUS_ACTIVE])
            ->andWhere(['not', ['current_period_end' => null]])
            ->all();

        foreach ($activeSubscriptions as $subscription) {
            $daysUntilRenewal = $subscription->getDaysUntilRenewal();
            
            // Send notification if subscription renews in 7 days
            if ($daysUntilRenewal == 7) {
                self::sendSubscriptionRenewalNotification($subscription->user, $subscription);
            }
        }
    }

    /**
     * Send welcome email to new user
     */
    public static function sendWelcomeEmail($user)
    {
        try {
            return Yii::$app->mailer->compose('welcome', [
                'user' => $user,
                'academyUrl' => $user->getAcademyUrl(),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject('Welcome to Vult SaaS - Your Academy Management Platform')
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email verification
     */
    public static function sendEmailVerification($user)
    {
        try {
            return Yii::$app->mailer->compose('emailVerification', [
                'user' => $user,
                'verificationUrl' => Yii::$app->urlManager->createAbsoluteUrl([
                    'site/verify-email',
                    'token' => $user->verification_token
                ]),
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Vult SaaS'])
            ->setTo($user->email)
            ->setSubject('Verify your email address - Vult SaaS')
            ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send email verification: ' . $e->getMessage());
            return false;
        }
    }
}
