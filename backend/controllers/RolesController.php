<?php

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\Contactus;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\models\search\RbacAuthItemSearch;
use backend\modules\rbac\models\RbacAuthItem;
use backend\modules\rbac\models\RbacAuthItemChild;

/**
 * ContactusController implements the CRUD actions for Contactus model.
 */
class RolesController extends BackendController
{

    /**
     * Lists all Contactus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RbacAuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['and',['!=','assignment_category','NULL'],
                ['!=','assignment_category', RbacAuthItem::CUSTOM_ROLE_ASSIGN]]);

        if($searchModel->assignment_category == RbacAuthItem::ACTION_ASSIGN){
            $dataProvider->query->andWhere(['assignment_category'=> RbacAuthItem::ACTION_ASSIGN]);
        }else{
            $dataProvider->query->andWhere(['!=','assignment_category',RbacAuthItem::ACTION_ASSIGN]);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    /**
     * Displays a single RbacAuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RbacAuthItem::find()->joinWith('rbacAuthItemChildren0')
                ->where(['parent'=>$id,'type'=>2, 'assignment_category'=> RbacAuthItem::ACTION_ASSIGN]),
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'roleName'=> $id
        ]);
    }

    /**
     * Creates a new RbacAuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parentName=null)
    {
        $model = new RbacAuthItem();
        $model->assignment_category = RbacAuthItem::CONTROLLER_ASSIGN;
        if($parentName){
            $model->itemParent = $parentName;
            $model->type=2;
            $model->isParent = 0;
            $model->assignment_category = RbacAuthItem::ACTION_ASSIGN;
            $model->name = $parentName . '_';
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            if(!$model->isParent)
            {
                $childItem = new RbacAuthItemChild();
                $childItem->parent = $model->itemParent;
                $childItem->child = $model->name;
                $childItem->save();
            }
            if($parentName){
                return $this->redirect(['view','id'=>$parentName]);
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'childView'=> $parentName ? 1 : 0
        ]);
    }

    /**
     * Updates an existing RbacAuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id,$parentName=null)
    {
        $model = $this->findModel($id);
        if($parentName){
            $model->itemParent = $parentName;
        }else{
            if($model->roleMainParent){
                $model->isParent = 0;
                $model->itemParent = $model->roleMainParent->parent;
            }else{
                $model->isParent = 1;
            }
        }
        $oldParent = $model->itemParent;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if($oldParent != $model->itemParent){
                RbacAuthItemChild::deleteAll(['parent'=>$oldParent, 'child'=>$model->name]);
                if(!$model->isParent)
                {
                    $childItem = new RbacAuthItemChild();
                    $childItem->parent = $model->itemParent;
                    $childItem->child = $model->name;
                    $childItem->save();
                }
            }
            
            if($parentName){
                return $this->redirect(['view','id'=>$parentName]);
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'childView'=> $parentName ? 1 : 0
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
}
