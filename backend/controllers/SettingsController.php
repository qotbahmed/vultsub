<?php

namespace backend\controllers;

use backend\models\AccountForm;
use Yii;
use backend\models\Settings;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends BackendController
{
    public function beforeAction($action) 
    {               
        $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        reset($roles);        
        $role = current($roles);        

        $controller = \Yii::$app->controller->id;       
        $action = \Yii::$app->controller->action->id;

        if($role->name === "manager"){            
            return Yii::$app->user->identity->checkPermissions($controller.'_'.$action) ?  : $this->redirect('/') ;
        }else{
            return true;
        } 
    }
    
//    public function beforeAction($action)
//    {
//        if(isset($_SESSION['MenuV']) && $_SESSION['MenuV'] =='dry'){
//            return Yii::$app->user->identity->checkPermmissions('dry_settings')?: $this->redirect('/') ;
//
//        }else{
//            return Yii::$app->user->identity->checkPermmissions('settings')?: $this->redirect('/') ;
//
//        }
//    }
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex($tab = 'point')
    {
        // Load models
        $settingsModel = isset($_SESSION['MenuV']) && $_SESSION['MenuV'] == 'dry' ? $this->findModel(2) : $this->findModel(1);
        $profileModel = Yii::$app->user->identity->userProfile;
        $userModel = Yii::$app->user->identity; // Load User model
        $accountModel = new AccountForm();

        // Handle form submission based on the active tab
        if ($tab === 'point' && $settingsModel->loadAll(Yii::$app->request->post()) && $settingsModel->saveAll()) {
            Yii::$app->session->setFlash('alert', [
                'type' => 'success',
                'body' => Yii::t('backend', 'تم تحديث البيانات بنجاح'),
            ]);
            return $this->redirect(['index', 'tab' => 'point']);
        }

        elseif ($tab === 'account' && $accountModel->load(Yii::$app->request->post()) && $accountModel->validate()) {
            $user = Yii::$app->user->identity;
            $user->username = $accountModel->username;
            $user->email = $accountModel->email;
            if ($accountModel->password) {
                $user->setPassword($accountModel->password);
            }
            if ($user->save()) {
                Yii::$app->session->setFlash('alert', [
                    'type' => 'success',
                    'body' => Yii::t('backend', 'تم حفظ الحساب بنجاح'),
                ]);
                return $this->redirect(['index', 'tab' => 'account']);
            }
        }

        elseif ($tab === 'privacy' && $profileModel->load(Yii::$app->request->post()) && $userModel->load(Yii::$app->request->post())) {

            // Save User model (email)
            if ($userModel->validate() && $userModel->save(false)) {
                if ($profileModel->save()) {
                    Yii::$app->session->setFlash('alert', [
                        'type' => 'success',
                        'body' => Yii::t('backend', 'تم حفظ الملف الشخصي بنجاح'),
                    ]);
                    return $this->redirect(['index', 'tab' => 'privacy']);
                }
            }
        }

        return $this->render('index', [
            'settingsModel' => $settingsModel,
            'profileModel' => $profileModel,
            'userModel' => $userModel,
            'accountModel' => $accountModel,

            'tab' => $tab
        ]);
    }

    /**
     * Displays a single Settings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Settings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Settings();
        $render_data = [
            'model' => $model
        ];

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' =>  Yii::t('backend', 'Settings was successfuly created'),
                'title' =>'',
            ]);

            if (!Yii::$app->request->isAjax) {
                return $this->redirect(['index']);
            } else {
                return $this->actionUpdate($model->id);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', $render_data);
        } else {
            return $this->render('create', $render_data);
        }
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

    }

    /**
     * Deletes an existing Settings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionProfile()
    {
        $model = Yii::$app->user->identity->userProfile;
        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('backend', 'Your profile has been successfully saved')
            ]);
            return $this->refresh();
        }else{
            //  var_dump($model->errors);die;
        }
        return $this->render('profile', ['model' => $model]);
    }

    public function actionAccount()
    {
        $user = Yii::$app->user->identity;
        $model = new AccountForm();
        $model->username = $user->username;
        $model->email = $user->email;
        if ($model->load($_POST) && $model->validate()) {
            $user->username = $model->username;
            $user->email = $model->email;
            if ($model->password) {
                $user->setPassword($model->password);
            }
            if( $user->save()){
                Yii::$app->session->setFlash('alert', [
                    'options' => ['class' => 'alert-success'],
                    'body' => Yii::t('backend', 'Your account has been successfully saved')
                ]);
            }else{
                var_dump($user->errors); die;
            }
            return $this->refresh();
        }
        return $this->render('account', ['model' => $model]);
    }
    
    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
}
