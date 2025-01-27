<?php

namespace backend\controllers;

use trntv\filekit\actions\UploadAction;
use Yii;
use yii\helpers\Json;
use common\models\User;

use yii\filters\VerbFilter;
use backend\models\UserForm;
use yii\helpers\ArrayHelper;
use common\models\UserProfile;
use yii\web\NotFoundHttpException;
use backend\models\search\UserSearch;
use trntv\filekit\actions\DeleteAction;
use Intervention\Image\ImageManagerStatic;
use backend\modules\rbac\models\RbacAuthItem;

/**
 * UserController implements the CRUD actions for User model.
 */
class DashboardManagersController extends BackendController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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
            ]
        ];
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $searchModel->role_category = RbacAuthItem::CUSTOM_ROLE_ASSIGN;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'defaultOrder' => [ 'id'=>SORT_DESC ],
        ]);

        return $this->render('/dashboard-managers/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        $model = $this->findModel($id);

        User::CheckProfile($model);
        $avaiableCategories = UserProfile::customRolePermissionsList($model->id);
        return $this->render('/dashboard-managers/view', [
            'model' =>$model,
            'categories'=>$avaiableCategories
        ]);
    }

    public function actionCreate()
    {
        $model = new UserForm();
        $profile= new UserProfile();
        $model->status= User::STATUS_ACTIVE;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $profile->load(Yii::$app->request->post());
            $this->UpdateUserRelatedTbls($model,$profile);

            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' => \Yii::t('backend', 'Data has been saved Successfully') ,
                'title' =>'',
            ]);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm();
        $model->setModel($this->findModel($id));
        $model->roles = ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($id), 'name');
        $profile= $model->getModel()->userProfile;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $profile->load(Yii::$app->request->post());
            $this->UpdateUserRelatedTbls($model,$profile);


            Yii::$app->getSession()->setFlash('alert', [
                'type' =>'success',
                'body' => \Yii::t('backend', 'Data has been updated Successfully') ,
                'title' =>'',
            ]);
            if (Yii::$app->user->can('customRole')){
                return $this->redirect(['/user/index']);
            }else{
                return $this->redirect(['index']);
            }
        }
        return $this->render('/dashboard-managers/update', [
            'model' => $model,
            'profile' => $profile
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //delete is soft delete
         $user=   $this->findModel($id);
         $user->status = User::STATUS_DELETED ;
          $user->save(false);

        Yii::$app->getSession()->setFlash('alert', [
            'type' =>'success',
            'body' => \Yii::t('backend', 'Data has been updated Successfully') ,
            'title' =>'',
        ]);


//        Yii::$app->authManager->revokeAll($id);
//        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
            $prof->user_id=$model->getModel()->id;
        }
        $prof->locale= 'ar-AR';
        $prof->firstname = $profile->firstname ;
        $prof->lastname = $profile->lastname ;
        $prof->gender = $profile->gender;
        $prof->avatar_base_url = isset($profile->picture['base_url']) ? $profile->picture['base_url'] : null;
        $prof->avatar_path = isset($profile->picture['path'])? $profile->picture['path']: null ;
        $prof->save(false);

        return true ;
    }

    public function actionFindOwner($id=null){

        $out = [];
        if (isset($id) ) {
            $userObj = User::find()->where(['school_id'=>$id])->all();

            if ($userObj) {
                return Json::encode(['output' => $userObj ,'status'=>1]);
            }
            return  Json::encode(['output' => '','status'=>0]);
        }

    }

}
