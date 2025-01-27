<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace frontend\controllers;

use frontend\models\LoginForm;
use cheatsheet\Time;
use frontend\models\ParentSignup;
use frontend\modules\user\models\SignupForm;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

use yii\web\UploadedFile;

class SignInController extends FrontController
{

    public $defaultAction = 'login';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'avatar-upload' => [
                'class' => UploadAction::class,
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::class
            ],

            'shop-upload' => [
                'class' => UploadAction::class,
                'deleteRoute' => 'shop-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'shop-delete' => [
                'class' => DeleteAction::class
            ],


            'gallery-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'gallery-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'gallery-delete' => [
                'class' => DeleteAction::className()
            ],

        ];
    }

    

    /**
     * @return string|Response
     */
    public function actionSignup()
    {                
        $this->layout = 'base';
        \Yii::$app->view->title = "التسجيل";

        $model = new ParentSignup();

        if (empty($model->email)) {
            $model->email =  'parent' . uniqid(). '@example.com';
        }
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $user = $model->signup();
                if ($user) {

                    Yii::$app->session->setFlash('alert',  \Yii::t('common',
                        'Your Account has been Created Successfully'));

                    return $this->redirect(['login']);
                } else {
                    Yii::$app->getSession()->setFlash('alert',
                         Yii::t('common', 'Something is wrong'));
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        $this->layout = 'base';
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['profile/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['profile/index']);
        } else {
            return $this->render('login', [
                'model' => $model
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->goHome();
        return $this->redirect(['/sign-in/login']);

    }

    public function actionProfile()
    {
        $model = Yii::$app->user->identity->userProfile;        
        $shop = Yii::$app->user->identity->shop;        

        if ($model->load($_POST) && $model->save()) 
        {            
            // $model->cr_document = UploadedFile::getInstance($model, 'cr_document');
            
            $shop->load($_POST);
            $this->UpdateUserRelatedTbls($shop);

            Yii::$app->session->setFlash('alert', [
                'type' => 'success',
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('backend', 'Your profile has been successfully saved', [], $model->locale)
            ]);
            return $this->refresh();
        }else{
        //    var_dump($model->errors);die;
        }
        return $this->render('profile', ['model' => $model, 'userProfile'=> $userProfile, 'shop' => $shop]);
    }

    // needs to be edited


    public function actionAccount()
    {
        $user = Yii::$app->user->identity;
        $profile = $user->userProfile;
        
        $model = new AccountForm();
        $model->username = $user->username;
        $model->email = $user->email;
        $model->full_name = $user->full_name;

        if ($model->load($_POST) && $model->validate()) 
        {
            $user->username = $model->username;
            $user->email = $model->email;
            $user->full_name = $model->full_name;

            $profile->load($_POST);
            $profile->avatar_base_url= isset($profile->picture['base_url']) ? $profile->picture['base_url'] : null;
            $profile->avatar_path= isset($profile->picture['path'])? $profile->picture['path']: null ;
            $profile->save(false);

            if ($model->password) {
                $user->setPassword($model->password);
            }
            if( $user->save())
            {                            
                Yii::$app->session->setFlash('alert', [
                    'type' => 'success',
                    'options' => ['class' => 'alert-success'],
                    'body' => Yii::t('backend', 'Your account has been successfully saved')
                ]);
            }else{
                var_dump($user->errors); die;
            }
            return $this->refresh();
        }
        return $this->render('account', ['profile'=>$profile, 'model' => $model]);
    }

    // THE CONTROLLER


    
}
