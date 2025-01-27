<?php

namespace backend\controllers;

use backend\models\search\UserSearch;
use backend\models\UserForm;
use common\models\User;
use common\models\UserProfile;
use common\models\UserToken;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\helpers\Helper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BackendController
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


    public function actionIndex($user_type = null)
    {
        if ($user_type !== null) {
            Yii::$app->session->set('userType', $user_type);
        } else {
            $user_type = Yii::$app->session->get('userType', 0);
        }

        $query = User::find()->where(['user_type' => $user_type]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $searchModel = new UserSearch();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'userType' => $user_type,
        ]);
    }



    public function actionCreate()
    {
        if (!Yii::$app->user->can('administrator')) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' => 'success',
                'body' => \Yii::t('backend', 'You are not allowed'),
                'title' => '',
            ]);
            //   return $this->redirect('index');
            return $this->redirect(['index', 'user_type' => Yii::$app->request->get('user_type')]);

        }

        $model = new UserForm();
        $profile = new UserProfile();
        $profile->locale = 'en-US';
        $model->status = User::STATUS_ACTIVE;
        $model->country_code = $model->country_code ?? 'EG';
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $profile->load(Yii::$app->request->post());
            $this->UpdateUserRelatedTbls($model, $profile);
            Yii::$app->getSession()->setFlash('alert', [
                'type' => 'success',
                'body' => \Yii::t('backend', 'Data has been saved Successfully'),
                'title' => '',
            ]);
            // return $this->redirect(['index']);
            return $this->redirect(['index', 'user_type' => Yii::$app->request->get('user_type')]);

        }

        if ($model->hasErrors()) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' => 'danger',
                'body' => \Yii::t('backend', 'There are some errors: ') . implode(', ', $model->getFirstErrors()),
                'title' => '',
            ]);
        }



        return $this->render('create', [
            'model' => $model,
            'profile' => $profile,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
        ]);
    }



    public function actionUpdate($id)
    {
        $model = new UserForm();
        $model->setModel($this->findModel($id));
        $profile = $model->getModel()->userProfile;

        $model->country_code = $model->country_code ?? 'EG';


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $profile->load(Yii::$app->request->post());
            $this->UpdateUserRelatedTbls($model, $profile);

            Yii::$app->getSession()->setFlash('alert', [
                'type' => 'success',
                'body' => \Yii::t('backend', 'Data has been updated successfully'),
                'title' => '',
            ]);
            return $this->redirect(['index', 'user_type' => Yii::$app->request->get('user_type')]);

            //  return $this->redirect(['index']);
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
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws NotFoundHttpException
     */
    public function actionLogin($id)
    {
        $model = $this->findModel($id);
        $tokenModel = UserToken::create(
            $model->getId(),
            UserToken::TYPE_LOGIN_PASS,
            60
        );

        return $this->redirect(
            Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/sign-in/login-by-pass', 'token' => $tokenModel->token])
        );
    }

    public function actionDelete($id)

    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('alert', [
                'type' => 'success',
                'body' => Yii::t('backend', 'User has been deleted successfully'),
            ]);
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('alert', [
                'type' => 'danger',
                'body' => Yii::t('backend', 'Error deleting user'),
            ]);
        }
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


    public function UpdateUserRelatedTbls($model, $profile, $post_data = null)
    {
        $prof = $model->getModel()->userProfile;

        if (!$prof) {
            $prof = new UserProfile();
            $prof->user_id = $model->getId();
        }
        $prof->locale = 'en-US';
        $prof->firstname = $profile->firstname;
        $prof->lastname = $profile->lastname ?? null;
        $prof->gender = $profile->gender;
        $prof->academy_id = $profile->academy_id;
        $prof->avatar_base_url = isset($profile->picture['base_url']) ? $profile->picture['base_url'] : null;
        $prof->avatar_path = isset($profile->picture['path']) ? $profile->picture['path'] : null;
        $prof->save(false);


        return true;
    }

    public function actionBranches()
    {
        $id = Yii::$app->request->post('id');

        if (!$id) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['error' => 'No ID provided'];
        }

        // Fetch branches where the parent_id matches the provided ID

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Prepare response data
        $response = [];


        return $response;
    }

    public function actionToggleStatus($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = User::findOne($id);

        if ($model) {
            $model->status = $model->status === User::STATUS_ACTIVE ? User::STATUS_NOT_ACTIVE : User::STATUS_ACTIVE;
            $newStatusText = $model->status === User::STATUS_ACTIVE ? 'Active' : 'Inactive';

            if ($model->save()) {
                return [
                    'success' => true,
                    'newStatusText' => $newStatusText,
                ];
            }
        }

        return ['success' => false];
    }
    public function actionSearchFullNames($q = null)
    {
        // If no query provided, return an empty array
        if (!$q) {
            return [];
        }

        // Use Yii2's ActiveQuery to search for matching users
        $query = UserProfile::find()
            ->select(['user_profile.user_id', 'firstname AS fullName'])
            ->joinWith('user') // Ensure the user is joined
            ->where(['like', 'firstname', $q])
            ->andWhere(['user_type' => Yii::$app->session->get('userType')]);

        // Execute the query and return the results
        $results = $query->asArray()->all();

        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        return $results;
    }



}