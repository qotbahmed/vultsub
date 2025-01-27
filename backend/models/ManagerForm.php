<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Create user form
 */
class ManagerForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;
    public $status;
    public $roles;

    private $model;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                        $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                    }
                }
            ],
           ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->getModel()->id]]);
                }
            }],

            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],

            [['status'], 'integer'],
           // ['roles', 'string'],
            ['roles', 'safe'],
            // [['roles'], 'each',
            //    'rule' => ['in', 'range' => ArrayHelper::getColumn(
            //        Yii::$app->authManager->getRoles(),
            //        'name'
            //    )]
            // ],
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
        $this->username = $model->username;
        $this->email = $model->email;
        $this->status = $model->status;
        $this->model = $model;
        // $this->roles = ArrayHelper::getColumn(
        //     Yii::$app->authManager->getRolesByUser($model->getId()),
        //     'name'
        // );
        return $this->model;
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Username'),
            'email' => Yii::t('backend', 'Email'),
            'status' => Yii::t('backend', 'Status'),
            'password' => Yii::t('backend', 'Password'),
            'password_confirm' => Yii::t('backend', 'Password Confirm'),
            'roles' => Yii::t('backend', 'Roles')
        ];
    }

    /**
     * Signs user up.
     * @return User|null the saved model or null if saving fails
     * @throws Exception
     */
    public function save($role= 'user')
    {        
        if ($this->validate()) {
            Yii::$app->session->set('UserRole', "manager");

            $model = $this->getModel();
            $isNewRecord = $model->getIsNewRecord();
            $model->username = $this->username;
            $model->email = $this->email;
            $model->status = $this->status;
            if ($this->password) {
                $model->setPassword($this->password);
            }

            if ($this->roles && is_array($this->roles)) {
                // foreach ($this->roles as $role) {                
                //    $auth->assign($auth->getRole($role), $model->getId());
                // }

                $model->roles = implode(',', $this->roles);
            }

            if (!$model->save()) {
                throw new Exception('Model not saved');
                return $model->errors;

            }
            if ($isNewRecord) {
                $model->afterSignup();
            }

            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->getId());                                    

            $auth->assign($auth->getRole(Yii::$app->session->get('UserRole')) , $model->getId());

            // if($role){
            //     $auth->assign( $auth->getRole($role), $model->getId());

            // }else{
            //     $auth->assign( $auth->getRole($this->roles) , $model->getId());
            // }


            return !$model->hasErrors();
        }
        return null;
    }

    public function checkPermissionsInUpdate($role)
    {
        $user = $this->getModel();
        
        $roles = explode(',', $user->roles);

        if(in_array($role, $roles))
            return true;
        
        return false;
    }
}
