<?php

namespace backend\controllers;
use Yii;
use common\models\User;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\helpers\CommonHelper;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\modules\rbac\models\RbacAuthItem;
use backend\modules\rbac\models\RbacAuthItemChild;


class UserCustomRoleController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator', 'superAdmin'],
                    ]

                ], // rules
            ], // access
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], // verbs
        ]; // return
    }
    /**
     * Lists all Contactus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RbacAuthItem::find()->where(['type'=>1,'assignment_category'=> RbacAuthItem::CUSTOM_ROLE_ASSIGN])
                ->andWhere(['!=','name','customRole']),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RbacAuthItem model.
     * @param string $id
     * @return mixed
     */
    // public function actionView($id)
    // {
    //     $dataProvider = new ActiveDataProvider([
    //         'query' => RbacAuthItem::find()->joinWith('rbacAuthItemChildren0')
    //             ->where(['parent'=>$id,'type'=>2,'created_by_admin'=>1]),
    //     ]);

    //     return $this->render('view', [
    //         'dataProvider' => $dataProvider,
    //         'roleName'=> $id
    //     ]);
    // }

    /**
     * Creates a new RbacAuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RbacAuthItem();
        $model->assignment_category = RbacAuthItem::CUSTOM_ROLE_ASSIGN;
        $model->type = 1;
        $model->scenario = 'addCustomRole';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->name = CommonHelper::generateSlug($model->name);
            $model->save();
            if($model->itemParent){
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->name;
                $childItem->child = $model->itemParent;
                $childItem->save();
            }else{
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->name;
                $childItem->child = 'customRole';
                $childItem->save();
            }
            
            foreach($model->subRoles as $subRole){
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->name;
                $childItem->child = $subRole;
                $childItem->save();
            }
            
            return $this->redirect(['index']);
        }

        $modules = RbacAuthItem::find()->where(['type'=>1,'assignment_category'=>RbacAuthItem::CATEGORY_ASSIGN])->all();

        return $this->render('create', [
            'model' => $model,
            'modules'=> $modules,
        ]);
    }

    /**
     * Updates an existing RbacAuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->userCustomRoleMainChild != 'customRole'){
            $model->itemParent = $model->userCustomRoleMainChild->child;
        }
        $model->scenario = 'addCustomRole';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            RbacAuthItemChild::deleteAll(['parent'=> $model->name]);
            
            if($model->itemParent){
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->name;
                $childItem->child = $model->itemParent;
                $childItem->save();
            }else{
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->name;
                $childItem->child = 'customRole';
                $childItem->save();
            }

            foreach($model->subRoles as $subRole){
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->name;
                $childItem->child = $subRole;
                if($childItem->validate()){
                    $childItem->save();
                }
            }
            return $this->redirect(['index']);
        }
        foreach($model->rbacAuthItemChildren as $action){
            if($action->child0->name == 'customRole')
                continue;
            $model->subRoles[] = $action->child0->name;
        }

        $modules = RbacAuthItem::find()->where(['type'=>1,'assignment_category'=>RbacAuthItem::CATEGORY_ASSIGN])->all();

        return $this->render('update', [
            'model' => $model,
            'modules'=> $modules,
        ]);
    }

    /**
     * Deletes an existing RbacAuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RbacAuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RbacAuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RbacAuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAssignContentEditorToCustom()
    {
        // /user-custom-role/assign-content-editor-to-custom
        Yii::$app->response->format='json';
        $contentEditors = User::find()->where(['in','id',
            Yii::$app->authManager->getUserIdsByRole(User::ROLE_CONTENT_EDITOR)])->all();
        foreach($contentEditors as $contentEditor){
            $auth = Yii::$app->authManager;
            $auth->revokeAll($contentEditor->id);

            $auth->assign($auth->getRole('custom-content-editor'), $contentEditor->id);
        }
        return 'done';
    }
}
