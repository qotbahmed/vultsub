<?php

namespace common\models;

use backend\modules\rbac\models\RbacAuthAssignment;
use backend\modules\rbac\models\RbacAuthItem;
use common\commands\AddToTimelineCommand;
use common\models\query\UserQuery;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $auth_key
 * @property string $access_token
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $publicIdentity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $logged_at
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property boolean $is_trial
 * @property string $trial_ends_at
 * @property integer $academy_id
 * @property string $subdomain
 * @property string $academy_name
 * @property integer $branches_count
 * @property string $subscription_status
 * @property string $subscription_ends_at
 * @property string $stripe_customer_id
 * @property string $stripe_subscription_id
 * @property integer $plan_id
 * @property boolean $email_verified
 * @property string $email_verified_at
 * @property integer $mobile
 * @property string $rate_average
 * @property integer $user_type
 * @property integer $rejected_status
 * @property boolean $available_for_booking
 * @property string $password write-only password
 * @property array $files
 *
 * @property \common\models\UserProfile $userProfile
 * @property integer $trial_started_at
 * @property integer $trial_expires_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const REJECTED_BY_ADMIN = 1;
    const APPROVED_BY_ADMIN = 0;
    public $distance = '';
    public $password_holder = '';

    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    const APPROVAL_NOT_ACTIVE = 0;
    const APPROVAL_ACTIVE = 1;

    const USER_TYPE_ACADEMY_ADMIN = 3;

    const ROLE_USER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMINISTRATOR = 'administrator';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    const USER_TYPE_CUSTOMER = 0; // Add this line
    public $user_role;
    /**
     * @var array
     */
    public $files;
    public $close;
    public $parentName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'notifySignup']);
        $this->on(self::EVENT_AFTER_DELETE, [$this, 'notifyDeletion']);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->active()
            ->andWhere(['id' => $id])
            ->one();
    }


    /**
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->active()
            ->andWhere(['access_token' => $token])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|array|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->active()
            ->andWhere(['username' => $username])
            ->one();
    }

    /**
     * Finds user by username or email
     *
     * @param string $login
     * @return User|array|null
     */
    public static function findByLogin($login)
    {
        return static::find()
            ->active()
            ->andWhere(['or', ['username' => $login], ['email' => $login]])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'auth_key' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString()
            ],
            'access_token' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'access_token'
                ],
                'value' => function () {
                    return Yii::$app->getSecurity()->generateRandomString(40);
                }
            ],


        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'oauth_create' => [
                    'oauth_client', 'oauth_client_user_id', 'email', 'username', '!status'
                ],
                'create' => [
                    'username', 'email', 'password', 'mobile', 'status', 'first_name', 'last_name', 'academy_name', 'subdomain', 'phone', 'branches_count'
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['username', 'email', 'mobile', 'subdomain'], 'unique'],
            ['username', 'string', 'min' => 2, 'message' => 'يجب أن لا يقل اسم المدير عن حرفين.'],
            [['mobile'], 'match', 'pattern' => '/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            [['first_name', 'last_name', 'academy_name', 'subdomain'], 'required'],
            [['first_name', 'last_name', 'academy_name', 'subdomain'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 255],
            [['branches_count', 'plan_id', 'academy_id'], 'integer'],
            [['is_trial', 'email_verified'], 'boolean'],
            [['subscription_status'], 'string', 'max' => 50],
            [['trial_ends_at', 'subscription_ends_at', 'email_verified_at'], 'safe'],

            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['approval', 'default', 'value' => self::APPROVAL_NOT_ACTIVE],
            ['wallet_last_update', 'default', 'value' => strtotime(date("Y-m-d H:i"))],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['username'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['password_reset_token, approval, firebase_token, wallet, wallet_last_update, roles', 'safe'],
            ['available_for_booking', 'boolean'],
            [['rate_average'], 'number'],
            [['password_holder'], 'safe'],
            [['user_type', 'rejected_status', 'close'], 'integer'],
            ['parent_id', 'integer'],
            ['mobile', 'match',
                'pattern' => '/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/',
                'message' => 'أدخل رقم جوال سعودي'],
            [['files', 'close'], 'safe'],
        ];
    }

    /**
     * Returns user statuses list
     * @return array|mixed
     */
    public static function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }

    public function approvals()
    {
        return [
            self::APPROVAL_NOT_ACTIVE => Yii::t('backend', 'Not Approved'),
            self::APPROVAL_ACTIVE => Yii::t('backend', 'Approved'),
        ];
    }

    public static function getStatuses($controllerType)
    {
        $statuses = [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
        ];

        return $statuses;
    }


    public static function getApprovals()
    {
        $statuses = [
            self::APPROVAL_NOT_ACTIVE => Yii::t('backend', 'Not Approved'),
            self::APPROVAL_ACTIVE => Yii::t('backend', 'Approved'),
        ];

        return $statuses;
    }


    public function usersStatuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => '<span class="status-slot btn-danger">' . Yii::t('backend', 'Not Active') . '</span>',
            self::STATUS_ACTIVE => '<span class="status-slot btn-primary">' . Yii::t('backend', 'Active') . '</span>'
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Username'),
            'firstname' => Yii::t('backend', 'Fullname'),
            'email' => Yii::t('backend', 'Email'),
            'password' => Yii::t('backend', 'Password'),
            'mobile' => Yii::t('backend', 'Mobile'),
            'status' => Yii::t('backend', 'Status'),
            'access_token' => Yii::t('backend', 'API access token'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated at'),
            'logged_at' => Yii::t('backend', 'Last login'),
            'user_type' => Yii::t('backend', 'User Type'),
            'customer_relative' => Yii::t('backend', 'Customer Relative'),

        ];
    }


    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }



    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function myRole($role)
    {
        return static::find()
            ->join('LEFT JOIN', '{{rbac_auth_assignment}}', "{{rbac_auth_assignment}}.user_id = id")
            ->where(['{{rbac_auth_assignment}}.item_name' => $role, 'user_id' => $this->id])
            ->one();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Creates user profile and application event
     * @param array $profileData
     */
    public function afterSignup(array $profileData = [])
    {
        $this->refresh();
        $profile = new UserProfile();
        $profile->locale = Yii::$app->language;
        $profile->load($profileData, '');
        $this->link('userProfile', $profile);
        $this->trigger(self::EVENT_AFTER_SIGNUP);
        // Default role
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(User::ROLE_USER), $this->getId());
    }

    public function notifySignup()
    {

        $this->refresh();
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'user_id' => $this->getId(),
            'event' => 'signup',
            'data' => [
                'type' => $this->user_type,
                'public_identity' => $this->getPublicIdentity(),
                'user_id' => $this->getId(),
                'created_at' => $this->created_at
            ]
        ]));
    }

    public function notifyRequest($requesr_id)
    {

        $this->refresh();
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'user_id' => $this->getId(),
            'event' => 'request',
            'data' => [
                'type' => $this->getUserType(),
                'public_identity' => $this->getPublicIdentity(),
                'request_id' => $requesr_id,
                'created_at' => $this->created_at
            ]
        ]));
    }


    public function notifyDeletion($event)
    {
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'event' => 'delete',
            'data' => [
                'public_identity' => $this->getPublicIdentity(),
                'user_id' => $this->getId(),
                'deleted_at' => time()
            ]
        ]));
    }

    /**
     * @return string
     */
    public function getPublicIdentity()
    {
        if ($this->userProfile && $this->userProfile->getFullname()) {
            return $this->userProfile->getFullname();
        }
        if ($this->username) {
            return $this->username;
        }
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function CountUsers($role, $andWhere = '1=1')
    {

        return static::find()
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->join('LEFT JOIN', 'user_profile', 'user_profile.user_id = id')
            ->where(['rbac_auth_assignment.item_name' => $role])
            ->andWhere($andWhere)
            ->count();
    }

    public static function IsRole($userID, $role)
    {
        $roles = ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($userID), 'name');
        $currentRole = array_keys($roles)[0];
        if ($currentRole == $role) {
            return true;
        } else {
            return false;
        }
    }

    public static function getPreferredAgeUnit($unit)
    {
        $translations = [
            1 => Yii::t('backend', 'Day(s)'),
            2 => Yii::t('backend', 'Month(s)'),
            3 => Yii::t('backend', 'Year(s)'),
            // Add more translations if needed
        ];

        return $translations[$unit] ?: ' ';
    }


    public function checkPermissions($permission)
    {
        $roles = explode(',', $this->roles);

        if (in_array($permission, $roles))
            return true;

        return false;
    }


    public function checkMenuPermissions($permission)
    {
        // Logged in user role
        $user_roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        reset($user_roles);
        $user_role = current($user_roles);

        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;


        $roles = explode(',', $this->roles);

        if (in_array($permission, $roles) && $user_role->name === "manager")
            return true;

        return false;
    }


    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }


    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }


    public function deleteWithRelated()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add additional deletion logic here, if necessary.

            if ($this->delete() === false) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    public static function ListRoles($withoutParentRole = false)
    {
        $roles = RbacAuthItem::find()->where(['and', ['type' => 1],
            ['assignment_category' => NULL],
            ['!=', 'name', 'administrator'],
            ['!=', 'name', 'customRole']]);
        if ($withoutParentRole && !Yii::$app->user->can('administrator')) {
            $roles = $roles->andWhere(['!=', 'name', User::ROLE_PARENT]);
        }
        $roles = $roles->select(['name', 'description'])->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

    public static function ListCustomRoles($limit = null)
    {
        $roles = RbacAuthItem::find()->where(['and', ['type' => 1],
            ['assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN]])
            ->select(['name', 'description'])->limit($limit)->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted'),
        ];
    }

    public function getUserRole()
    {
        $role = $this->hasOne(RbacAuthAssignment::class,['user_id' => 'id'])->with(['itemName'])->one();
        return $role->itemName;
    }

    /**
     * Check if user is on trial
     * @return bool
     */
    public function isTrial()
    {
        return $this->trial_expires_at && $this->trial_expires_at > time();
    }

    /**
     * Check if trial has expired
     * @return bool
     */
    public function isTrialExpired()
    {
        return $this->trial_expires_at && $this->trial_expires_at <= time();
    }

    /**
     * Get remaining trial days
     * @return int
     */
    public function getTrialDaysLeft()
    {
        if (!$this->trial_expires_at) {
            return 0;
        }
        
        $daysLeft = ceil(($this->trial_expires_at - time()) / (24 * 60 * 60));
        return max(0, $daysLeft);
    }

    /**
     * Find user by email
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Start trial period for user
     * @param int $days Number of trial days (default 7)
     * @return bool
     */
    public function startTrial($days = 7)
    {
        $this->trial_started_at = time();
        $this->trial_expires_at = time() + ($days * 24 * 60 * 60);
        return $this->save();
    }

    /**
     * End trial period for user
     * @return bool
     */
    public function endTrial()
    {
        $this->trial_expires_at = time();
        return $this->save();
    }

}