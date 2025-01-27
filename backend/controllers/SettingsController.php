<?php

namespace backend\controllers;

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
    public function actionIndex()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Settings::find(),
//        ]);
//
//        $dataProvider->setSort([
//            'defaultOrder' => ['id' => SORT_DESC ],
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);

        if(isset($_SESSION['MenuV']) && $_SESSION['MenuV'] =='dry'){
            $model = $this->findModel(2);
        }else{
            $model = $this->findModel(1);

        }

        $render_data = [
            'model' => $model
        ];

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {

            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' =>  Yii::t('backend', 'Data has been updated successfully'),
                'title' =>'',
            ]);

            if (!Yii::$app->request->isAjax) {
                return $this->redirect(['index']);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', $render_data);
        } else {
            return $this->render('update', $render_data);
        }
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
