<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\models\LoginForm;
use common\models\AcademyRequest;
use common\models\User;
use frontend\models\RegisterForm;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Auth controller for authentication
 */
class AuthController extends Controller
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
                        'actions' => ['login', 'register', 'unified-login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'unified-login' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            
            if ($model->login()) {
                Yii::$app->session->setFlash('success', 'تم تسجيل الدخول بنجاح! مرحباً بك في Vult.');
                return $this->goBack();
            } else {
                // Display validation errors
                $errors = $model->getFirstErrors();
                if (!empty($errors)) {
                    $errorMessage = 'حدث خطأ في البيانات المدخلة:<br>';
                    foreach ($errors as $field => $error) {
                        $errorMessage .= '• ' . $error . '<br>';
                    }
                    Yii::$app->session->setFlash('error', $errorMessage);
                } else {
                    Yii::$app->session->setFlash('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة.');
                }
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Register action.
     *
     * @return string|Response
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            
            if ($model->register()) {
                Yii::$app->session->setFlash('success', 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.');
                return $this->redirect(['login']);
            } else {
                // Display validation errors
                $errors = $model->getFirstErrors();
                if (!empty($errors)) {
                    $errorMessage = 'حدث خطأ في البيانات المدخلة:<br>';
                    foreach ($errors as $field => $error) {
                        $errorMessage .= '• ' . $error . '<br>';
                    }
                    Yii::$app->session->setFlash('error', $errorMessage);
                } else {
                    Yii::$app->session->setFlash('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Unified login API endpoint.
     *
     * @return Response
     */
    public function actionUnifiedLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
        Yii::$app->response->headers->add('Access-Control-Allow-Methods', 'POST');
        Yii::$app->response->headers->add('Access-Control-Allow-Headers', 'Content-Type');

        if (Yii::$app->request->isPost) {
            $input = Yii::$app->request->getBodyParams();
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            
            if (!$email || !$password) {
                Yii::$app->response->statusCode = 400;
                return ['error' => 'Email and password are required'];
            }
            
            try {
                // Check user in database
                $user = User::findByEmail($email);
                
                if (!$user || !$user->validatePassword($password)) {
                    Yii::$app->response->statusCode = 401;
                    return ['error' => 'Invalid credentials'];
                }
                
                // Check if user is active
                if ($user->status != User::STATUS_ACTIVE) {
                    Yii::$app->response->statusCode = 403;
                    return ['error' => 'Account is inactive'];
                }
                
                // Check trial status
                $trialActive = false;
                $trialDaysLeft = 0;
                
                if ($user->trial_expires_at) {
                    $trialActive = $user->trial_expires_at > time();
                    $trialDaysLeft = $trialActive ? max(0, ceil(($user->trial_expires_at - time()) / (24 * 60 * 60))) : 0;
                }
                
                // Determine redirect URL
                $redirectUrl = '';
                
                if ($trialActive) {
                    $redirectUrl = Yii::$app->urlManager->createAbsoluteUrl(['dashboard/trial-dashboard']);
                } else {
                    $redirectUrl = Yii::$app->urlManager->createAbsoluteUrl(['home/pricing']);
                }
                
                // Generate session token
                $sessionToken = bin2hex(random_bytes(32));
                
                // Store session in database (you might want to create a UserSession model)
                // For now, we'll just return the token
                
                return [
                    'success' => true,
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'data' => [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'trial_active' => $trialActive,
                        'trial_days_left' => $trialDaysLeft,
                        'redirect_url' => $redirectUrl,
                        'session_token' => $sessionToken
                    ]
                ];
                
            } catch (\Exception $e) {
                Yii::$app->response->statusCode = 500;
                return ['error' => 'Database error: ' . $e->getMessage()];
            }
        } else {
            Yii::$app->response->statusCode = 405;
            return ['error' => 'Method not allowed'];
        }
    }
}