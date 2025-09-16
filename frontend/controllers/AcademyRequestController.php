<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\AcademyRequest;
use common\models\AcademyRequestSearch;
use common\models\Academies;
use common\models\User;

/**
 * AcademyRequestController implements the CRUD actions for AcademyRequest model.
 */
class AcademyRequestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'approve' => ['POST'],
                    'reject' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AcademyRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AcademyRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AcademyRequest model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AcademyRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AcademyRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AcademyRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AcademyRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Approves an AcademyRequest model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        
        if ($model->status !== AcademyRequest::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'Request is not pending.');
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            
            // Approve the request
            $model->approve();
            
            // Create academy in portal database
            $academy = new Academies();
            $academy->title = $model->academy_name;
            $academy->contact_email = $model->email;
            $academy->contact_phone = $model->phone;
            $academy->address = $model->address;
            $academy->city_id = 1; // Default city
            $academy->district_id = 1; // Default district
            $academy->description = $model->description;
            $academy->manager_id = $model->user_id;
            $academy->main = 1;
            $academy->created_by = Yii::$app->user->id;
            $academy->status = 1;
            $academy->subscription_plan = 'trial';
            $academy->subscription_status = 'active';
            $academy->subscription_start = date('Y-m-d H:i:s');
            $academy->subscription_end = date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60)); // 7 days
            $academy->trial_start = time();
            $academy->trial_end = time() + (7 * 24 * 60 * 60);
            $academy->trial_status = 'active';
            
            if (!$academy->save()) {
                throw new \Exception('Failed to create academy: ' . implode(', ', $academy->getFirstErrors()));
            }
            
            // Update academy request with portal academy ID
            $model->portal_academy_id = $academy->id;
            $model->save();
            
            // Update user with academy ID
            $user = User::findOne($model->user_id);
            if ($user) {
                $user->academy_id = $academy->id;
                $user->save();
            }
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Academy request approved successfully.');
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Failed to approve request: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Rejects an AcademyRequest model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReject($id)
    {
        $model = $this->findModel($id);
        
        if ($model->status !== AcademyRequest::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'Request is not pending.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $notes = Yii::$app->request->post('notes');
        if ($notes) {
            $model->notes = $notes;
        }

        if ($model->reject()) {
            Yii::$app->session->setFlash('success', 'Academy request rejected successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to reject request.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the AcademyRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AcademyRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AcademyRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
