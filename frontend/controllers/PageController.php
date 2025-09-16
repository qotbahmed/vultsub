<?php

namespace frontend\controllers;

use trntv\filekit\actions\UploadAction;
use Yii;
use common\models\Page;
use common\models\search\PageSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\helpers\MyUploadAction;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends BackendController
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

    public function actions() {
        return [

            'image-upload' => [
                'class' => UploadAction::class,
                // 'class' => MyUploadAction::class,
                'deleteRoute' => 'image-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->resize(1000, 600);
                    $file->put($img->encode());
                },
            ],
            'image-delete' => [
                'class' => DeleteAction::class,
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        $render_data = [
            'model' => $model
        ];

        $model->status = 1 ;

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' =>  Yii::t('backend', 'Page was successfuly created'),
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
     * Updates an existing Page model.
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
                'message' => Yii::t('backend', 'Page was successfuly updated'),
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
     * Deletes an existing Page model.
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
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
}
