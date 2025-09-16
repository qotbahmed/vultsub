<?php

namespace common\helpers;

use Yii;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Price;
use Stripe\PaymentMethod;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;
use common\models\User;
use common\models\Subscription as UserSubscription;
use common\models\SubscriptionPlan;

class StripeHelper
{
    public static function init()
    {
        Stripe::setApiKey(Yii::$app->params['stripe']['secretKey']);
    }

    /**
     * Create a Stripe customer
     */
    public static function createCustomer($user)
    {
        self::init();
        
        try {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->getFullName(),
                'metadata' => [
                    'user_id' => $user->id,
                    'academy_name' => $user->academy_name,
                    'subdomain' => $user->subdomain,
                ]
            ]);

            $user->stripe_customer_id = $customer->id;
            $user->save(false);

            return $customer;
        } catch (ApiErrorException $e) {
            Yii::error('Stripe customer creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a subscription
     */
    public static function createSubscription($user, $plan, $paymentMethodId, $trialDays = null)
    {
        self::init();
        
        try {
            // Create customer if not exists
            if (!$user->stripe_customer_id) {
                self::createCustomer($user);
            }

            // Attach payment method to customer
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
            $paymentMethod->attach([
                'customer' => $user->stripe_customer_id,
            ]);

            // Set as default payment method
            Customer::update($user->stripe_customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);

            // Create subscription
            $subscriptionData = [
                'customer' => $user->stripe_customer_id,
                'items' => [
                    [
                        'price' => $plan->getStripePriceId('monthly'), // Default to monthly
                    ],
                ],
                'default_payment_method' => $paymentMethodId,
                'expand' => ['latest_invoice.payment_intent'],
            ];

            // Add trial if specified
            if ($trialDays) {
                $subscriptionData['trial_period_days'] = $trialDays;
            }

            $stripeSubscription = Subscription::create($subscriptionData);

            // Create local subscription record
            $subscription = new UserSubscription();
            $subscription->user_id = $user->id;
            $subscription->plan_id = $plan->id;
            $subscription->status = $stripeSubscription->status;
            $subscription->stripe_subscription_id = $stripeSubscription->id;
            $subscription->stripe_customer_id = $user->stripe_customer_id;
            $subscription->current_period_start = date('Y-m-d H:i:s', $stripeSubscription->current_period_start);
            $subscription->current_period_end = date('Y-m-d H:i:s', $stripeSubscription->current_period_end);
            $subscription->amount = $stripeSubscription->items->data[0]->price->unit_amount / 100;
            $subscription->currency = $stripeSubscription->currency;
            $subscription->interval = $stripeSubscription->items->data[0]->price->recurring->interval;
            $subscription->interval_count = $stripeSubscription->items->data[0]->price->recurring->interval_count;

            if ($stripeSubscription->trial_start) {
                $subscription->trial_start = date('Y-m-d H:i:s', $stripeSubscription->trial_start);
                $subscription->trial_end = date('Y-m-d H:i:s', $stripeSubscription->trial_end);
            }

            $subscription->save();

            // Update user subscription status
            $user->subscription_status = User::SUBSCRIPTION_STATUS_ACTIVE;
            $user->subscription_ends_at = $subscription->current_period_end;
            $user->is_trial = 0;
            $user->save(false);

            return $subscription;

        } catch (ApiErrorException $e) {
            Yii::error('Stripe subscription creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel subscription
     */
    public static function cancelSubscription($subscription, $atPeriodEnd = true)
    {
        self::init();
        
        try {
            $stripeSubscription = Subscription::retrieve($subscription->stripe_subscription_id);
            
            if ($atPeriodEnd) {
                $stripeSubscription->cancel_at_period_end = true;
                $stripeSubscription->save();
                
                $subscription->cancel_at_period_end = 1;
            } else {
                $stripeSubscription->cancel();
                $subscription->status = UserSubscription::STATUS_CANCELED;
                $subscription->canceled_at = date('Y-m-d H:i:s');
            }
            
            $subscription->save();
            
            return true;
        } catch (ApiErrorException $e) {
            Yii::error('Stripe subscription cancellation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update subscription plan
     */
    public static function updateSubscriptionPlan($subscription, $newPlan, $interval = 'monthly')
    {
        self::init();
        
        try {
            $stripeSubscription = Subscription::retrieve($subscription->stripe_subscription_id);
            
            Subscription::update($subscription->stripe_subscription_id, [
                'items' => [
                    [
                        'id' => $stripeSubscription->items->data[0]->id,
                        'price' => $newPlan->getStripePriceId($interval),
                    ],
                ],
                'proration_behavior' => 'create_prorations',
            ]);

            $subscription->plan_id = $newPlan->id;
            $subscription->amount = $newPlan->getPriceForPeriod($interval);
            $subscription->save();

            return true;
        } catch (ApiErrorException $e) {
            Yii::error('Stripe subscription update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle webhook events
     */
    public static function handleWebhook($payload, $signature)
    {
        self::init();
        
        try {
            $event = Webhook::constructEvent($payload, $signature, Yii::$app->params['stripe']['webhookSecret']);
            
            switch ($event->type) {
                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                    self::handleSubscriptionUpdated($event->data->object);
                    break;
                    
                case 'customer.subscription.deleted':
                    self::handleSubscriptionDeleted($event->data->object);
                    break;
                    
                case 'invoice.payment_succeeded':
                    self::handlePaymentSucceeded($event->data->object);
                    break;
                    
                case 'invoice.payment_failed':
                    self::handlePaymentFailed($event->data->object);
                    break;
            }
            
            return true;
        } catch (\Exception $e) {
            Yii::error('Webhook handling failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle subscription updated event
     */
    private static function handleSubscriptionUpdated($stripeSubscription)
    {
        $subscription = UserSubscription::findByStripeSubscriptionId($stripeSubscription->id);
        
        if ($subscription) {
            $subscription->status = $stripeSubscription->status;
            $subscription->current_period_start = date('Y-m-d H:i:s', $stripeSubscription->current_period_start);
            $subscription->current_period_end = date('Y-m-d H:i:s', $stripeSubscription->current_period_end);
            $subscription->cancel_at_period_end = $stripeSubscription->cancel_at_period_end;
            
            if ($stripeSubscription->canceled_at) {
                $subscription->canceled_at = date('Y-m-d H:i:s', $stripeSubscription->canceled_at);
            }
            
            $subscription->save();
            
            // Update user status
            $user = $subscription->user;
            if ($user) {
                $user->subscription_status = $stripeSubscription->status === 'active' ? 
                    User::SUBSCRIPTION_STATUS_ACTIVE : User::SUBSCRIPTION_STATUS_CANCELLED;
                $user->subscription_ends_at = $subscription->current_period_end;
                $user->save(false);
            }
        }
    }

    /**
     * Handle subscription deleted event
     */
    private static function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscription = UserSubscription::findByStripeSubscriptionId($stripeSubscription->id);
        
        if ($subscription) {
            $subscription->status = UserSubscription::STATUS_CANCELED;
            $subscription->canceled_at = date('Y-m-d H:i:s');
            $subscription->save();
            
            // Update user status
            $user = $subscription->user;
            if ($user) {
                $user->subscription_status = User::SUBSCRIPTION_STATUS_CANCELLED;
                $user->save(false);
            }
        }
    }

    /**
     * Handle payment succeeded event
     */
    private static function handlePaymentSucceeded($invoice)
    {
        // Log successful payment
        Yii::info('Payment succeeded for invoice: ' . $invoice->id);
    }

    /**
     * Handle payment failed event
     */
    private static function handlePaymentFailed($invoice)
    {
        // Handle failed payment
        $subscription = UserSubscription::findByStripeCustomerId($invoice->customer);
        
        if ($subscription) {
            $subscription->status = UserSubscription::STATUS_PAST_DUE;
            $subscription->save();
            
            // Update user status
            $user = $subscription->user;
            if ($user) {
                $user->subscription_status = User::SUBSCRIPTION_STATUS_PAST_DUE;
                $user->save(false);
            }
        }
    }

    /**
     * Get customer's payment methods
     */
    public static function getPaymentMethods($customerId)
    {
        self::init();
        
        try {
            return PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
            ]);
        } catch (ApiErrorException $e) {
            Yii::error('Failed to get payment methods: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Create payment intent for one-time payment
     */
    public static function createPaymentIntent($amount, $currency = 'usd', $customerId = null)
    {
        self::init();
        
        try {
            $intentData = [
                'amount' => $amount * 100, // Convert to cents
                'currency' => $currency,
            ];
            
            if ($customerId) {
                $intentData['customer'] = $customerId;
            }
            
            return PaymentIntent::create($intentData);
        } catch (ApiErrorException $e) {
            Yii::error('Failed to create payment intent: ' . $e->getMessage());
            throw $e;
        }
    }
}
