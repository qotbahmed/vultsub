<?php

namespace academy\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Subscription;
use common\models\SubscriptionPlan;
use common\helpers\StripeHelper;
use frontend\models\SubscriptionForm;

/**
 * Subscription controller
 */
class SubscriptionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays subscription management page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $subscription = Subscription::find()->where(['user_id' => $user->id])->one();
        $plans = SubscriptionPlan::getActivePlans();

        return $this->render('index', [
            'user' => $user,
            'subscription' => $subscription,
            'plans' => $plans,
        ]);
    }

    /**
     * Subscribe to a plan
     */
    public function actionSubscribe()
    {
        $user = Yii::$app->user->identity;
        $planId = Yii::$app->request->post('plan_id');
        $interval = Yii::$app->request->post('interval', 'monthly');
        $paymentMethodId = Yii::$app->request->post('payment_method_id');

        if (!$planId || !$paymentMethodId) {
            Yii::$app->session->setFlash('error', 'Missing required parameters.');
            return $this->redirect(['index']);
        }

        $plan = SubscriptionPlan::findOne($planId);
        if (!$plan) {
            Yii::$app->session->setFlash('error', 'Invalid plan selected.');
            return $this->redirect(['index']);
        }

        try {
            // Create subscription
            $subscription = StripeHelper::createSubscription($user, $plan, $paymentMethodId);
            
            Yii::$app->session->setFlash('success', 'Your subscription has been activated successfully!');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Failed to create subscription: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Cancel subscription
     */
    public function actionCancel()
    {
        $user = Yii::$app->user->identity;
        $subscription = Subscription::find()->where(['user_id' => $user->id])->one();

        if (!$subscription) {
            Yii::$app->session->setFlash('error', 'No active subscription found.');
            return $this->redirect(['index']);
        }

        try {
            StripeHelper::cancelSubscription($subscription, true); // Cancel at period end
            
            Yii::$app->session->setFlash('success', 'Your subscription will be cancelled at the end of the current billing period.');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Failed to cancel subscription: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Update subscription plan
     */
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $subscription = Subscription::find()->where(['user_id' => $user->id])->one();
        $planId = Yii::$app->request->post('plan_id');
        $interval = Yii::$app->request->post('interval', 'monthly');

        if (!$subscription) {
            Yii::$app->session->setFlash('error', 'No active subscription found.');
            return $this->redirect(['index']);
        }

        $plan = SubscriptionPlan::findOne($planId);
        if (!$plan) {
            Yii::$app->session->setFlash('error', 'Invalid plan selected.');
            return $this->redirect(['index']);
        }

        try {
            StripeHelper::updateSubscriptionPlan($subscription, $plan, $interval);
            
            Yii::$app->session->setFlash('success', 'Your subscription plan has been updated successfully!');
            return $this->redirect(['index']);
            
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Failed to update subscription: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Get Stripe publishable key for frontend
     */
    public function actionGetStripeKey()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        return [
            'publishableKey' => Yii::$app->params['stripe']['publishableKey'],
        ];
    }

    /**
     * Create payment intent for subscription
     */
    public function actionCreatePaymentIntent()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $user = Yii::$app->user->identity;
        $planId = Yii::$app->request->post('plan_id');
        $interval = Yii::$app->request->post('interval', 'monthly');

        $plan = SubscriptionPlan::findOne($planId);
        if (!$plan) {
            return ['error' => 'Invalid plan selected.'];
        }

        try {
            $amount = $plan->getPriceForPeriod($interval);
            $intent = StripeHelper::createPaymentIntent($amount, 'usd', $user->stripe_customer_id);
            
            return [
                'clientSecret' => $intent->client_secret,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
