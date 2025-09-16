<?php

namespace frontend\controllers;

use backend\models\ManagerForm;
use common\models\UserProfile;
use common\models\UserStatusLogs;
use common\helpers\NotificationHelper;
use common\models\CustomerRequest;
use common\models\RequestLog;
use backend\models\Settings;
use Yii;
use common\models\User;
use backend\models\search\UserSearch;

use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

use yii\helpers\ArrayHelper;

/**
 * NannyController implements the CRUD actions for User model.
 */
class ManagersController extends BackendController
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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->session->set('UserRole', "manager");

        $searchModel = new UserSearch();
        $searchModel->user_role = Yii::$app->session->get('UserRole');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->setSort([
            'defaultOrder' => [ 'id' => SORT_DESC ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        $model = new ManagerForm();
        $profile= new UserProfile();
        // $model->setScenario('create');

        $model->status = User::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $profile->load(Yii::$app->request->post());

            $this->UpdateUserRelatedTbls($model, $profile);

            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' => \Yii::t('backend', 'Data has been saved Successfully') ,
                'title' =>'',
            ]);

            return $this->redirect(['index']);
        }

        // var_dump(ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'name'));die();

        return $this->render('create', [
            'model' => $model,
            'profile'=>$profile,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name')
        ]);
    }


    public function actionUpdate($id)
    {        
        $model = new ManagerForm();
        $model->setModel($this->findModel($id));
        
        $profile= $model->getModel()->userProfile;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $profile->load(Yii::$app->request->post());
            $this->UpdateUserRelatedTbls($model,$profile);
            
            //var_dump( $model->permission); die;

            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' => \Yii::t('backend', 'Data has been updated Successfully') ,
                'title' =>'',
            ]);

            return $this->redirect(['index']);
        }


        return $this->render('update', [
            'model' => $model,
            'profile' => $profile,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
        ]);
    }


    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function UpdateUserRelatedTbls($model,$profile,$post_data=null){
        $prof= $model->getModel()->userProfile;
        if(!$prof) {
            $prof = new UserProfile();
            $prof->user_id=$model->getId();
        }
        $prof->locale = 'en-US';
        $prof->firstname = $profile->firstname ;
        $prof->lastname = $profile->lastname ;
        $prof->gender= $profile->gender;
        $prof->avatar_base_url= isset($profile->picture['base_url']) ? $profile->picture['base_url'] : null;
        $prof->avatar_path= isset($profile->picture['path'])? $profile->picture['path']: null ;
        $prof->save(false);

        return true ;
    }

}