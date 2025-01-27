<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use common\models\search\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BackendController
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerFaq = new \yii\data\ArrayDataProvider([
            'allModels' => $model->faqs,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerFaq' => $providerFaq,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $render_data = [
            'model' => $model
        ];

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' =>  Yii::t('backend', 'Category was successfully created'),
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
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $render_data = [
            'model' => $model
        ];

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => Yii::t('backend', 'Category was successfully updated'),
            ]);
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
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Faq
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddFaq()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Faq');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('action') == 'load' && empty($row)) || Yii::$app->request->post('action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formFaq', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
}
