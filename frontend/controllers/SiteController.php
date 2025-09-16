<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\AcademyRequest;
use common\models\User;
use common\models\UserSession;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Get statistics
        $stats = [
            'total_requests' => AcademyRequest::find()->count(),
            'pending_requests' => AcademyRequest::find()->where(['status' => AcademyRequest::STATUS_PENDING])->count(),
            'approved_requests' => AcademyRequest::find()->where(['status' => AcademyRequest::STATUS_APPROVED])->count(),
            'rejected_requests' => AcademyRequest::find()->where(['status' => AcademyRequest::STATUS_REJECTED])->count(),
            'total_users' => User::find()->count(),
            'active_sessions' => UserSession::find()->where(['>', 'expires_at', time()])->count(),
        ];

        // Get recent requests
        $recentRequests = AcademyRequest::find()
            ->orderBy(['requested_at' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('index', [
            'stats' => $stats,
            'recentRequests' => $recentRequests,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \common\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}