<?php

namespace frontend\controllers;

use Yii;
use backend\models\City;
use backend\models\search\CitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends BackendController
{

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

    public function actions() {
        return [
            'change-sort' => [
                'class' => 'demi\sort\SortAction',
                'modelClass' => City::className(),
            ],

        ];
    }

    /**
     * Lists all City models.
     * @return mixed
     */
    public function actionIndex($governmentID=null)
    {
        if($governmentID != ""){
            $_SESSION['governmentID'] = $governmentID ;
            $governorate = \backend\models\Government::findOne($governmentID);
            $_SESSION['governmentTitle'] = $governorate->title ;

        }
        $searchModel = new CitySearch();
        $searchModel->government_id = $_SESSION['governmentID'];

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'defaultOrder' => [ 'sort' => SORT_ASC ],
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single City model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerCustomer = new \yii\data\ArrayDataProvider([
            'allModels' => $model->customers,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerCustomer' => $providerCustomer,
        ]);
    }

    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new City();
        $model->government_id= $_SESSION['governmentID'];
        $render_data = [
            'model' => $model
        ];


        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' =>  Yii::t('backend', 'City was successfuly created'),
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
     * Updates an existing City model.
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
                'message' => Yii::t('backend', 'City was successfuly updated'),
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
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();

        return $this->redirect(['index']);
    }
    
    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Customer
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddCustomer()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Customer');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('action') == 'load' && empty($row)) || Yii::$app->request->post('action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formCustomer', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
}
