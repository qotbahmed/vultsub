<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;
use common\models\AcademyRequest;

/**
 * Dashboard controller for authenticated users
 */
class DashboardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['trial-dashboard', 'admin-dashboard', 'academy-management', 'players-management'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Trial dashboard for trial users.
     *
     * @return string
     */
    public function actionTrialDashboard()
    {
        $user = Yii::$app->user->identity;
        
        // Get trial data
        $trialActive = false;
        $trialDaysLeft = 0;
        
        if ($user->trial_expires_at) {
            $trialActive = $user->trial_expires_at > time();
            $trialDaysLeft = $trialActive ? max(0, ceil(($user->trial_expires_at - time()) / (24 * 60 * 60))) : 0;
        }
        
        // Get academy request data
        $academyRequest = AcademyRequest::find()->where(['user_id' => $user->id])->one();
        
        $trialData = [
            'academy_name' => $academyRequest->academy_name ?? 'غير محدد',
            'user_email' => $user->email,
            'request_status' => $academyRequest->status ?? 'pending',
            'is_trial_active' => $trialActive,
            'trial_days_left' => $trialDaysLeft,
        ];

        return $this->render('trial-dashboard', [
            'trialData' => $trialData,
        ]);
    }

    /**
     * Admin dashboard for administrators.
     *
     * @return string
     */
    public function actionAdminDashboard()
    {
        // Get statistics
        $stats = [
            'total_requests' => AcademyRequest::find()->count(),
            'pending_requests' => AcademyRequest::find()->where(['status' => AcademyRequest::STATUS_PENDING])->count(),
            'approved_requests' => AcademyRequest::find()->where(['status' => AcademyRequest::STATUS_APPROVED])->count(),
            'rejected_requests' => AcademyRequest::find()->where(['status' => AcademyRequest::STATUS_REJECTED])->count(),
            'total_users' => User::find()->count(),
        ];

        // Get recent requests
        $recentRequests = AcademyRequest::find()
            ->orderBy(['requested_at' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('admin-dashboard', [
            'stats' => $stats,
            'recentRequests' => $recentRequests,
        ]);
    }

    /**
     * Academy management dashboard.
     *
     * @return string
     */
    public function actionAcademyManagement()
    {
        return $this->render('academy-management');
    }

    /**
     * Players management dashboard.
     *
     * @return string
     */
    public function actionPlayersManagement()
    {
        return $this->render('players-management');
    }
}