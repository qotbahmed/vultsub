<?php

namespace academy\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Academies;

/**
 * Site controller for academy management
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error', 'trial-signup'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $user = Yii::$app->user->identity;
        $academy = $user->academy;
        
        if (!$academy) {
            Yii::$app->session->setFlash('error', 'No academy found for this user.');
            return $this->redirect(['trial-signup']);
        }

        // Check if user is on trial and if trial has expired
        if ($user->isTrialExpired()) {
            Yii::$app->session->setFlash('warning', 'Your trial has expired. Please upgrade to continue using the platform.');
            return $this->redirect(['/pricing']);
        }
        
        // Check if subscription is active
        $subscription = $academy->getActiveSubscriptions();
        if ($subscription == 0 && !$user->isTrial()) {
            Yii::$app->session->setFlash('error', 'Your subscription is not active. Please update your payment method.');
            return $this->redirect(['/billing']);
        }

        $stats = $this->getDashboardStats($user, $academy);
        
        return $this->render('dashboard', [
            'user' => $user,
            'academy' => $academy,
            'subscription' => $subscription,
            'stats' => $stats,
        ]);
    }

    /**
     * Login action.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \academy\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Trial signup action.
     *
     * @return mixed
     */
    public function actionTrialSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \academy\models\TrialSignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Trial account created successfully! You can now login.');
            return $this->redirect(['login']);
        }

        return $this->render('trial-signup', [
            'model' => $model,
        ]);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats($user, $academy)
    {
        return [
            'totalPlayers' => $this->getTotalPlayers($academy),
            'totalActivities' => $this->getTotalActivities($academy),
            'totalRevenue' => $this->getTotalRevenue($academy),
            'activeSubscriptions' => $academy->getActiveSubscriptions(),
            'trialDaysLeft' => $user->getTrialDaysLeft(),
        ];
    }

    /**
     * Get total players count
     */
    private function getTotalPlayers($academy)
    {
        // This would be implemented when player management is added
        return 127; // Dummy data for now
    }

    /**
     * Get total activities count
     */
    private function getTotalActivities($academy)
    {
        // This would be implemented when activity management is added
        return 23; // Dummy data for now
    }

    /**
     * Get total revenue
     */
    private function getTotalRevenue($academy)
    {
        // This would be implemented when payment tracking is added
        return 2450; // Dummy data for now
    }
}
