<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\AcademyRequest;
use frontend\models\Player;
use frontend\models\PlayerSearch;

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
                        'actions' => ['trial-dashboard', 'admin-dashboard', 'academy-management', 'players-management', 'debug-trial', 'debug-player'],
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
        $user = Yii::$app->user->identity;
        $searchModel = new PlayerSearch();
        
        // Set the academy_id filter to only show players from this user's academy
        $searchModel->academy_id = $user->academy_id;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        // Get statistics
        $stats = [
            'total_players' => Player::find()->where(['academy_id' => $user->academy_id])->count(),
            'active_players' => Player::find()->where(['academy_id' => $user->academy_id, 'status' => 'active'])->count(),
            'new_players' => Player::find()->where(['academy_id' => $user->academy_id])
                ->andWhere(['>=', 'created_at', date('Y-m-d', strtotime('-30 days'))])->count(),
            'average_attendance' => $this->calculateAverageAttendance($user->academy_id),
        ];
        
        // Get recent players
        $recentPlayers = Player::find()
            ->where(['academy_id' => $user->academy_id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();
        
        return $this->render('players-management', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stats' => $stats,
            'recentPlayers' => $recentPlayers,
        ]);
    }

    /**
     * Create a new player.
     *
     * @return string|Response
     */
    public function actionCreatePlayer()
    {
        $model = new Player();
        $user = Yii::$app->user->identity;
        
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                
                // Check if it's form validation request
                if (Yii::$app->request->post('ajax') === 'player-form') {
                    return ActiveForm::validate($model);
                }
                
                // Handle form submission
                $model->academy_id = $user->academy_id ?: 1; // Fallback to 1 if academy_id is null
                $model->status = 'active';
                
                // Debug: Log the model data
                Yii::info('Player model data: ' . json_encode($model->attributes), __METHOD__);
                
                if ($model->save()) {
                    return [
                        'success' => true,
                        'message' => 'تم إضافة اللاعب بنجاح!',
                        'redirect' => Yii::$app->urlManager->createUrl(['players-management'])
                    ];
                } else {
                    $errors = [];
                    foreach ($model->getErrors() as $field => $fieldErrors) {
                        $errors[$field] = $fieldErrors;
                    }
                    
                    // Debug: Log validation errors
                    Yii::error('Player validation errors: ' . json_encode($errors), __METHOD__);
                    
                    return [
                        'success' => false,
                        'message' => 'حدث خطأ في البيانات المدخلة',
                        'errors' => $errors
                    ];
                }
            } else {
                // Regular form submission (fallback)
                $model->academy_id = $user->academy_id;
                $model->status = 'active';
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'تم إضافة اللاعب بنجاح!');
                    return $this->redirect(['players-management']);
                } else {
                    $errors = $model->getFirstErrors();
                    if (!empty($errors)) {
                        $errorMessage = 'حدث خطأ في البيانات المدخلة:<br>';
                        foreach ($errors as $field => $error) {
                            $errorMessage .= '• ' . $error . '<br>';
                        }
                        Yii::$app->session->setFlash('error', $errorMessage);
                    }
                }
            }
        }
        
        return $this->renderAjax('_player_form', [
            'model' => $model,
        ]);
    }
    
    /**
     * Update an existing player.
     *
     * @param int $id
     * @return string|Response
     */
    public function actionUpdatePlayer($id)
    {
        $model = $this->findPlayer($id);
        $user = Yii::$app->user->identity;
        
        // Check if player belongs to user's academy
        // For now, allow access if either academy_id is null (for testing)
        if ($user->academy_id && $model->academy_id && $model->academy_id != $user->academy_id) {
            throw new NotFoundHttpException('اللاعب غير موجود.');
        }
        
        // Set scenario for update
        $model->scenario = 'update';
        
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                
                // Check if it's form validation request
                if (Yii::$app->request->post('ajax') === 'player-form') {
                    return ActiveForm::validate($model);
                }
                
                // Handle form submission
                // Debug: Log the model data
                Yii::info('Update Player model data: ' . json_encode($model->attributes), __METHOD__);
                
                if ($model->save()) {
                    return [
                        'success' => true,
                        'message' => 'تم تحديث بيانات اللاعب بنجاح!',
                        'redirect' => Yii::$app->urlManager->createUrl(['players-management'])
                    ];
                } else {
                    $errors = [];
                    foreach ($model->getErrors() as $field => $fieldErrors) {
                        $errors[$field] = $fieldErrors;
                    }
                    
                    // Debug: Log validation errors
                    Yii::error('Update Player validation errors: ' . json_encode($errors), __METHOD__);
                    
                    return [
                        'success' => false,
                        'message' => 'حدث خطأ في البيانات المدخلة',
                        'errors' => $errors
                    ];
                }
            } else {
                // Regular form submission (fallback)
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'تم تحديث بيانات اللاعب بنجاح!');
                    return $this->redirect(['players-management']);
                } else {
                    $errors = $model->getFirstErrors();
                    if (!empty($errors)) {
                        $errorMessage = 'حدث خطأ في البيانات المدخلة:<br>';
                        foreach ($errors as $field => $error) {
                            $errorMessage .= '• ' . $error . '<br>';
                        }
                        Yii::$app->session->setFlash('error', $errorMessage);
                    }
                }
            }
        }
        
        return $this->renderAjax('_player_form', [
            'model' => $model,
        ]);
    }
    
    /**
     * Delete a player.
     *
     * @param int $id
     * @return Response
     */
    public function actionDeletePlayer($id)
    {
        $model = $this->findPlayer($id);
        $user = Yii::$app->user->identity;
        
        // Check if player belongs to user's academy
        // For now, allow access if either academy_id is null (for testing)
        if ($user->academy_id && $model->academy_id && $model->academy_id != $user->academy_id) {
            throw new NotFoundHttpException('اللاعب غير موجود.');
        }
        
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'تم حذف اللاعب بنجاح!');
        } else {
            Yii::$app->session->setFlash('error', 'حدث خطأ أثناء حذف اللاعب.');
        }
        
        return $this->redirect(['players-management']);
    }
    
    /**
     * View player details.
     *
     * @param int $id
     * @return string
     */
    public function actionViewPlayer($id)
    {
        try {
            $model = $this->findPlayer($id);
            $user = Yii::$app->user->identity;
            
            // Debug logging
            Yii::info('View Player - ID: ' . $id . ', Player Academy ID: ' . $model->academy_id . ', User Academy ID: ' . $user->academy_id, __METHOD__);
            
            // Check if player belongs to user's academy
            // For now, allow access if either academy_id is null (for testing)
            if ($user->academy_id && $model->academy_id && $model->academy_id != $user->academy_id) {
                Yii::warning('Player access denied - Academy ID mismatch: Player=' . $model->academy_id . ', User=' . $user->academy_id, __METHOD__);
                throw new NotFoundHttpException('اللاعب غير موجود.');
            }
            
            return $this->renderAjax('_player_view', [
                'model' => $model,
            ]);
        } catch (NotFoundHttpException $e) {
            // Re-throw 404 exceptions
            throw $e;
        } catch (\Exception $e) {
            // Log other exceptions and return error
            Yii::error('Error in actionViewPlayer: ' . $e->getMessage(), __METHOD__);
            return $this->renderAjax('_player_view', [
                'model' => null,
                'error' => 'حدث خطأ في تحميل بيانات اللاعب: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Find player by ID.
     *
     * @param int $id
     * @return Player
     * @throws NotFoundHttpException
     */
    protected function findPlayer($id)
    {
        if (($model = Player::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('اللاعب غير موجود.');
    }
    
    /**
     * Calculate average attendance for academy.
     *
     * @param int $academyId
     * @return float
     */
    protected function calculateAverageAttendance($academyId)
    {
        // This is a placeholder - you would implement actual attendance calculation
        // based on your attendance tracking system
        return 85.5; // Return percentage
    }
    
    /**
     * Debug trial functionality
     */
    public function actionDebugTrial()
    {
        $user = Yii::$app->user->identity;
        
        echo "<h3>Trial Debug Info:</h3>";
        echo "<pre>";
        echo "User ID: " . $user->id . "\n";
        echo "User Email: " . $user->email . "\n";
        echo "Trial Started At: " . ($user->trial_started_at ? date('Y-m-d H:i:s', $user->trial_started_at) : 'Not set') . "\n";
        echo "Trial Expires At: " . ($user->trial_expires_at ? date('Y-m-d H:i:s', $user->trial_expires_at) : 'Not set') . "\n";
        echo "Current Time: " . date('Y-m-d H:i:s', time()) . "\n";
        echo "Is Trial Active: " . ($user->isTrial() ? 'Yes' : 'No') . "\n";
        echo "Is Trial Expired: " . ($user->isTrialExpired() ? 'Yes' : 'No') . "\n";
        echo "Trial Days Left: " . $user->getTrialDaysLeft() . "\n";
        echo "</pre>";
        
        // Test trial calculation
        $trialActive = false;
        $trialDaysLeft = 0;
        
        if ($user->trial_expires_at) {
            $trialActive = $user->trial_expires_at > time();
            $trialDaysLeft = $trialActive ? max(0, ceil(($user->trial_expires_at - time()) / (24 * 60 * 60))) : 0;
        }
        
        echo "<h4>Controller Calculation:</h4>";
        echo "<pre>";
        echo "Trial Active: " . ($trialActive ? 'Yes' : 'No') . "\n";
        echo "Trial Days Left: " . $trialDaysLeft . "\n";
        echo "</pre>";
        
        // Test academy request
        $academyRequest = AcademyRequest::find()->where(['user_id' => $user->id])->one();
        echo "<h4>Academy Request:</h4>";
        echo "<pre>";
        if ($academyRequest) {
            echo "Academy Name: " . $academyRequest->academy_name . "\n";
            echo "Status: " . $academyRequest->status . "\n";
        } else {
            echo "No academy request found\n";
        }
        echo "</pre>";
        
        // Test trial dashboard data
        $trialData = [
            'academy_name' => $academyRequest->academy_name ?? 'غير محدد',
            'user_email' => $user->email,
            'request_status' => $academyRequest->status ?? 'pending',
            'is_trial_active' => $trialActive,
            'trial_days_left' => $trialDaysLeft,
        ];
        
        echo "<h4>Trial Dashboard Data:</h4>";
        echo "<pre>" . json_encode($trialData, JSON_PRETTY_PRINT) . "</pre>";
    }

    /**
     * Debug action to test player update
     */
    public function actionDebugPlayer($id = null)
    {
        if ($id) {
            $model = $this->findPlayer($id);
            $user = Yii::$app->user->identity;
            
            echo "<h3>Player Debug Info:</h3>";
            echo "<pre>";
            echo "Player ID: " . $model->id . "\n";
            echo "Player Name: " . $model->name . "\n";
            echo "Player Academy ID: " . $model->academy_id . "\n";
            echo "User Academy ID: " . $user->academy_id . "\n";
            echo "Is New Record: " . ($model->isNewRecord ? 'Yes' : 'No') . "\n";
            echo "Scenario: " . $model->scenario . "\n";
            echo "Attributes: " . json_encode($model->attributes, JSON_PRETTY_PRINT) . "\n";
            echo "</pre>";
            
            // Test validation
            $model->scenario = 'update';
            if ($model->validate()) {
                echo "<p style='color: green;'>✅ Validation passed!</p>";
            } else {
                echo "<p style='color: red;'>❌ Validation failed:</p>";
                echo "<pre>" . json_encode($model->getErrors(), JSON_PRETTY_PRINT) . "</pre>";
            }
            
            // Test view player
            echo "<h4>Test View Player:</h4>";
            echo "<p><a href='" . Yii::$app->urlManager->createUrl(['view-player', 'id' => $id]) . "' target='_blank'>View Player Details</a></p>";
        } else {
            echo "<h3>Available Players:</h3>";
            $players = Player::find()->limit(5)->all();
            if (empty($players)) {
                echo "<p>No players found. <a href='?create=1'>Create a test player</a></p>";
            } else {
                foreach ($players as $player) {
                    echo "<p><a href='?id=" . $player->id . "'>" . $player->name . " (ID: " . $player->id . ")</a></p>";
                }
            }
            
            // Create test player if requested
            if (isset($_GET['create'])) {
                $testPlayer = new Player();
                $testPlayer->name = 'Test Player ' . time();
                $testPlayer->email = 'test' . time() . '@example.com';
                $testPlayer->phone = '1234567890';
                $testPlayer->date_of_birth = '2000-01-01';
                $testPlayer->sport = 'كرة القدم';
                $testPlayer->level = 'beginner';
                $testPlayer->status = 'active';
                $testPlayer->academy_id = 1;
                
                if ($testPlayer->save()) {
                    echo "<p style='color: green;'>✅ Test player created with ID: " . $testPlayer->id . "</p>";
                    echo "<p><a href='?id=" . $testPlayer->id . "'>View Test Player</a></p>";
                } else {
                    echo "<p style='color: red;'>❌ Failed to create test player:</p>";
                    echo "<pre>" . json_encode($testPlayer->getErrors(), JSON_PRETTY_PRINT) . "</pre>";
                }
            }
        }
    }
}