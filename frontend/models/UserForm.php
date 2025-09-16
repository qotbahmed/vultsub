<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Create user form
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $mobile;
    public $status;
    public $roles;

    private $model;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            [['email','roles'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],

            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],

            [['status'], 'integer'],
            ['mobile', 'string'],
            ['mobile', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],

//            [['roles'], 'each',
//                'rule' => ['in', 'range' => ArrayHelper::getColumn(
//                    Yii::$app->authManager->getRoles(),
//                    'name'
//                )]
//            ],
        ];
    }

    /**
     * @return User
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new User();
        }
        return $this->model;
    }

    /**
     * @param User $model
     * @return mixed
     */
    public function setModel($model)
    {
        $this->username = $model->email;
        $this->email = $model->email;
        $this->mobile = $model->mobile;
        $this->status = $model->status;
        $this->model = $model;
        $this->roles = ArrayHelper::getColumn(
            Yii::$app->authManager->getRolesByUser($model->getId()),
            'name'
        );
        return $this->model;
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Username'),
            'email' => Yii::t('common', 'Email'),
            'mobile' => Yii::t('common', 'Mobile'),
            'status' => Yii::t('common', 'Status'),
            'password' => Yii::t('common', 'Password'),
            'roles' => Yii::t('common', 'Role')
        ];
    }


public function save($role = 'user')
{
    if ($this->validate()) {
        $model = $this->getModel();
        $isNewRecord = $model->getIsNewRecord();
        $model->username = $this->email;
        $model->user_type = Yii::$app->session->get('userType');
        $model->email = $this->email;
        $model->mobile = $this->mobile;
        $model->status = $this->status;

        if ($this->password) {
            $model->setPassword($this->password);
        }

        if (!$model->save()) {
            Yii::error($model->errors, 'model'); // 
            return null;

        }

        if ($isNewRecord) {
            $model->afterSignup();
        }

        $oldRole = ArrayHelper::getColumn(
            Yii::$app->authManager->getRolesByUser($model->getId()),
            'name'
        );
        if($this->roles && $oldRole != $this->roles){
            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->getId());
            //assign only one role
            $auth->assign($auth->getRole($this->roles), $model->getId());
        }

        return !$model->hasErrors();
    }
    return null;
}


}
