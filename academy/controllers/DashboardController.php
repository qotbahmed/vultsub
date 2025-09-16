<?php

namespace academy\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Subscription;
use common\models\SubscriptionPlan;

/**
 * Dashboard controller
 */
class DashboardController extends Controller
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
     * Displays the academy dashboard.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $subscription = Subscription::find()->where(['user_id' => $user->id])->one();
        
        // Check if user is on trial and if trial has expired
        if ($user->isTrialExpired()) {
            Yii::$app->session->setFlash('warning', 'Your trial has expired. Please upgrade to continue using the platform.');
            return $this->redirect(['/subscription']);
        }
        
        // Check if subscription is active
        if ($subscription && !$subscription->isActive()) {
            Yii::$app->session->setFlash('error', 'Your subscription is not active. Please update your payment method.');
            return $this->redirect(['/billing']);
        }

        $stats = $this->getDashboardStats($user);
        
        return $this->render('index', [
            'user' => $user,
            'subscription' => $subscription,
            'stats' => $stats,
        ]);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats($user)
    {
        return [
            'totalStudents' => 0, // Will be implemented when student management is added
            'totalClasses' => 0,  // Will be implemented when class management is added
            'totalRevenue' => 0,  // Will be implemented when payment tracking is added
            'activeSubscriptions' => 0, // Will be implemented when subscription tracking is added
        ];
    }
}
