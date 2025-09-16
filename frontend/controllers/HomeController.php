<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\AcademyRequest;
use common\models\User;

/**
 * Home controller for public pages
 */
class HomeController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = 'public';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'pricing', 'academy-simple', 'test-academy', 'debug', 'info'],
                        'allow' => true,
                    ],
                ],
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
        return $this->render('index');
    }

    /**
     * Displays pricing page.
     *
     * @return string
     */
    public function actionPricing()
    {
        return $this->render('pricing');
    }

    /**
     * Displays academy simple dashboard (for authenticated users).
     *
     * @return string
     */
    public function actionAcademySimple()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['auth/login']);
        }

        // Get user statistics
        $user = Yii::$app->user->identity;
        $stats = [
            'total_players' => 127, // This should come from actual data
            'active_activities' => 23,
            'monthly_revenue' => 2450,
            'attendance_rate' => 94,
        ];

        return $this->render('academy-simple', [
            'stats' => $stats,
            'user' => $user,
        ]);
    }

    /**
     * Test academy page.
     *
     * @return string
     */
    public function actionTestAcademy()
    {
        return $this->render('test-academy');
    }

    /**
     * Debug page.
     *
     * @return string
     */
    public function actionDebug()
    {
        return $this->render('debug');
    }

    /**
     * Info page.
     *
     * @return string
     */
    public function actionInfo()
    {
        return $this->render('info');
    }
}