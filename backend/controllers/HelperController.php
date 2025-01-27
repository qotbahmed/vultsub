<?php

namespace backend\controllers;

use backend\models\City;
use backend\modules\rbac\models\RbacAuthItem;
use common\models\User;
use common\models\UserProfile;
use Yii;

use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

/**
 * SchoolsController implements the CRUD actions for Schools model.
 */
class HelperController extends   Controller
{


    public function actionListCustomers($q = null, $id = null) {
        $role = 'user';
        $q = preg_replace('/\s+/', '', $q);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            //$users= User::find()
            $query = new Query();
            $query->select('  `firstname` as text, user_profile.user_id as id')
                ->from('user_profile')
                ->join('LEFT JOIN','rbac_auth_assignment','rbac_auth_assignment.user_id = user_profile.user_id ')
                ->join('LEFT JOIN','user','user.id = user_profile.user_id ')
                ->where('`firstname` LIKE  \'%'.$q.'%\'   and rbac_auth_assignment.item_name ="'.$role.'"  and user_type=0' )
                ->limit(20);

            $command = $query->createCommand();

            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => UserProfile::find($id)->fullName];
        }
        return $out;
    }


    //Users
    public function actionUsersList($q = null, $id = null) {
        $role = Yii::$app->session->get('UserRole');
        $q = preg_replace('/\s+/', '', $q);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            //$users= User::find()
            $query = new Query();
            $query->select(' CONCAT_WS(" ", `firstname`, `lastname`) as text, user_profile.user_id as id')
                ->from('user_profile')
                ->join('LEFT JOIN','rbac_auth_assignment','rbac_auth_assignment.user_id = user_profile.user_id ')
                ->where('CONCAT( `firstname`, `lastname`) LIKE  \'%'.$q.'%\'   and rbac_auth_assignment.item_name ="'.$role.'" ' )
                ->limit(20);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => UserProfile::find($id)->fullName];
        }
        return $out;
    }

    public function  actionUsersListByRole($q = null, $id = null,$role=User::ROLE_USER) {

        $q = preg_replace('/\s+/', '', $q);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            //$users= User::find()
            $query = new Query();
            $query->select(' `firstname`  as text, user_profile.user_id as id')
                ->from('user_profile')
                ->join('LEFT JOIN','rbac_auth_assignment','rbac_auth_assignment.user_id = user_profile.user_id ')
                ->where(' `firstname` LIKE  \'%'.$q.'%\'    ' )
                ->limit(100);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => UserProfile::find($id)->fullName];
        }
        return $out;

    }



    //endpoints
    public function actionCity() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];

                $data = City::find()->where(['government_id'=>$cat_id])->all();

                foreach ($data as $datum) {
                    $out[] = ['id'=>$datum->id, 'name'=>$datum->title];

                }
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }


    public function actionRolePermissionsList($id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id) {
            $permissions = RbacAuthItem::find()->joinWith('rbacAuthItemChildren0')
                ->where(['parent'=>$id])->andWhere(['!=','assignment_category','NULL'])->all();

            $data = [];
            foreach ($permissions as $permission) {
                RbacAuthItem::getAllChildsKey($permission, $data);
            }
            $out['results'] = $data;
        }
        return $out;
    }

}
