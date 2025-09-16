<?php

namespace common\models;

use api\resources\SportResource;
use api\resources\SubscriptionDetailsResource;
use backend\modules\rbac\models\RbacAuthItem;
use common\commands\AddToTimelineCommand;
use common\models\query\UserQuery;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use common\models\Academies;
use common\models\CoachFile;
use common\models\CoachAppointment;
use trntv\filekit\behaviors\UploadBehavior;
use backend\modules\rbac\models\RbacAuthAssignment;


/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property string $firebase_token
 * @property string $access_token
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $publicIdentity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $logged_at
 * @property integer $mobile
 * @property string $rate_average
 * @property integer $user_type
 * @property integer $rejected_status
 * @property boolean $available_for_booking
 * @property string $password write-only password
 * @property array $files
 * @property CoachFile[] $coachFiles
 * 
 * @property \common\models\UserProfile $userProfile
 * @property \common\models\Academies $academy
 * @property integer $trial_started_at
 * @property integer $trial_expires_at
 * @property integer $academy_id
 */

class User extends ActiveRecord implements IdentityInterface
{
    const REJECTED_BY_ADMIN = 1;
    const APPROVED_BY_ADMIN = 0;
    public $distance = '';
    public $password_holder = '';
    public $user_role;

    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    const APPROVAL_NOT_ACTIVE = 0;
    const APPROVAL_ACTIVE = 1;

    const USER_TYPE_PARENT = 0;
    const USER_TYPE_PLAYER = 1;
    const USER_TYPE_TRAINER = 2;
    const USER_TYPE_ACADEMY_ADMIN = 3;
    const USER_TYPE_CUSTOM_ROLE = 4;
    const USER_TYPE_TALENT = 5;

    const ROLE_USER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMINISTRATOR = 'administrator';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    const AVAILABLE_FOR_BOOKING_YES = 1;
    const AVAILABLE_FOR_BOOKING_NO = 0;
    const USER_TYPE_CUSTOMER = 4; // Add this line

    /**
     * @var array
     */
    public $files;
    public $close;
    public $parentName;
    public $academy_id;


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
    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted'),
        ];
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

            [
                'class' => UploadBehavior::class,
                'attribute' => 'files',
                'multiple' => true,
                'uploadRelation' => 'coachFiles',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
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
                    'oauth_client',
                    'oauth_client_user_id',
                    'email',
                    'username',
                    '!status'
                ],
                'create' => [
                    'username',
                    'email',
                    'password',
                    'mobile',
                    'status'
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

            [['username'], 'unique'],

            ['username', 'string', 'min' => 2, 'message' => 'يجب أن لا يقل اسم المدير عن حرفين.'],
            [['mobile'], 'match', 'pattern' => '/^((009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})|(\+2|002|2)(10|11|12|15)([\d]{8})|(0)(10|11|12|15)([\d]{8}))$/'],

            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['approval', 'default', 'value' => self::APPROVAL_NOT_ACTIVE],
            ['wallet_last_update', 'default', 'value' => strtotime(date("Y-m-d H:i"))],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['username'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['password_reset_token, approval, firebase_token, wallet, wallet_last_update, roles', 'safe'],
            ['available_for_booking', 'boolean'],
            [['rate_average'], 'number'],
            ['mobile', 'validateMobileAcademy'],
            ['email', 'validateEmailAcademy'],
            [['password_holder', 'academy_id'], 'safe'],
            [['user_type', 'rejected_status', 'close'], 'integer'],
            ['parent_id', 'integer'],
            [
                'mobile',
                'match',
                'pattern' => '/^((009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})|(\+2|002|2)(10|11|12|15)([\d]{8})|(0)(10|11|12|15)([\d]{8}))$/',
                'message' => Yii::t('common', 'Enter a Saudi or Egyptian mobile number')
            ],
            [['files', 'close'], 'safe'],
            [['roles'], 'safe']
        ];
    }
    public static function statuse()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }
    /**
     * Returns user statuses list
     * @return array|mixed
     */
    public  function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('backend', 'Not Active'),
            self::STATUS_ACTIVE => Yii::t('backend', 'Active'),
            self::STATUS_DELETED => Yii::t('backend', 'Deleted'),
        ];
    }
    public  static function filterTypes()
    {
        return [
            '1' => Yii::t('backend', 'Nanny'),
            '2' => Yii::t('backend', 'Nurse'),
            '3' => Yii::t('backend', 'Orderly'),
        ];
    }

    public  function approvals()
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

    public function validateEmailAcademy($attribute, $params)
    {
        if (!$this->academy_id) {
            return;
        }

        $query = User::find()
            ->joinWith('userProfile')
            ->where([
                'user.email' => $this->email,
                'user_profile.academy_id' => $this->academy_id
            ]);

        if (!$this->getModel()->isNewRecord) {
            $query->andWhere(['<>', 'user.id', $this->getModel()->id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, Yii::t('common', 'Email is already registered in this academy.'));
        }
    }


    public function validateMobileAcademy($attribute, $params)
    {
        if (!$this->academy_id) {
            return;
        }

        $query = User::find()
            ->joinWith('userProfile')
            ->where([
                'user.mobile' => $this->mobile,
                'user_profile.academy_id' => $this->academy_id
            ]);

        if (!$this->getModel()->isNewRecord) {
            $query->andWhere(['<>', 'user.id', $this->getModel()->id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, Yii::t('common', 'Mobile number is already registered in this academy.'));
        }
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
            'parent_id' => Yii::t('backend', 'Parent'),
            'full_name' => Yii::t('backend', 'Fullname'),
            'roles' => Yii::t('common', 'Roles'),


        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademy()
    {
        return $this->hasOne(Academies::class, ['id' => 'academy_id'])
            ->via('userProfile');
    }
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function subscriptionCount()
    {
        return Subscription::find()
            ->where(['subscription_status' => Subscription::STATUS_ACTIVE])
            ->andwhere(['parent_id' => $this->id])
            ->count();
    }
    public function childCount()
    {
        return User::find()
            ->where(['parent_id' => $this->id])
            ->count();
    }
    public function currentSports()
    {
        return SportResource::find()
            ->innerJoinWith(['subscriptionDetails subscriptionDetails', 'subscriptionDetails.subscription subscription'])
            ->where(['subscriptionDetails.player_id' => $this->id])
            ->andWhere(['>=', 'subscriptionDetails.end_date', date('Y-m-d')])
            ->andWhere(['subscription.subscription_status' => Subscription::STATUS_ACTIVE])  // Check for subscription status
            ->all();
    }
    public function currentSubscriptionSports()
    {
        return  SubscriptionDetailsResource::find()->joinWith('subscription')
            ->where(['subscription.subscription_status' => Subscription::STATUS_ACTIVE])
            ->andwhere(['subscription_details.player_id' => $this->id])
            ->andWhere(['>=', 'subscription_details.end_date', date('Y-m-d')])
            ->all();
    }


    public function previousSubscriptionSports()
    {

        return SubscriptionDetails::find()
            ->joinWith('subscription')
            ->where(['subscription.subscription_status' => Subscription::STATUS_ACTIVE])
            ->andwhere(['subscription_details.player_id' => $this->id])
            ->andWhere(['<', 'subscription_details.end_date', date('Y-m-d')])
            ->all();
    }

    public function pendingSports()
    {
        return Sport::find()
            ->innerJoinWith(['subscriptionDetails subscriptionDetails', 'subscriptionDetails.subscription subscription'])
            ->where(['subscription.parent_id' => $this->id])
            ->andWhere(['>=', 'subscriptionDetails.end_date', date('Y-m-d')])
            ->andWhere(['subscription.subscription_status' => Subscription::STATUS_PENDING])  // Check for subscription status

            ->all();
    }
    public function currentSubscriptionCount()
    {
        return SubscriptionDetails::find()->joinWith('subscription')
            ->where(['subscription.subscription_status' => Subscription::STATUS_ACTIVE])
            ->andwhere(['subscription_details.player_id' => $this->id])
            ->andWhere(['>=', 'subscription_details.end_date', date('Y-m-d')])
            ->count();
    }
    public function previousSubscriptionCount()
    {
        return SubscriptionDetails::find()
            ->joinWith('subscription')
            ->where(['subscription.subscription_status' => Subscription::STATUS_ACTIVE])
            ->andwhere(['subscription_details.player_id' => $this->id])
            ->andWhere(['<', 'subscription_details.end_date', date('Y-m-d')])
            ->count();
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

        $currentAcademyId = Yii::$app->session->get('current_academy_id') ?? Yii::$app->user->identity->userProfile->academy_id;

        $this->refresh();
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'user_id' => $this->getId(),
            'academy_id' => $currentAcademyId,
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
        $currentAcademyId = Yii::$app->session->get('current_academy_id') ?? Yii::$app->user->identity->userProfile->academy_id;

        $this->refresh();
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'user_id' => $this->getId(),
            'academy_id' => $currentAcademyId,

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

    public static function  CountUsers($role, $andWhere = '1=1')
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
        $currentRole =   array_keys($roles)[0];
        if ($currentRole == $role) {
            return true;
        } else {
            return false;
        }
    }

    public function getSkills($getId)
    {
        $skills = UserSkills::find()->where(['user_id' => $getId])->all();
        $skillsResource = [];
        foreach ($skills as $skill) {
            /**
             * @var UserSkills $skill;
             */
            $skillsResource[] = [
                'title' => $skill->skill->title,
                'id' => $skill->skill->id,
            ];
        }
        return $skillsResource;
    }
    public function getLanguages($getId)
    {
        $languages = UserLanguge::find()->where(['user_id' => $getId])->all();
        $languagesResource = [];
        foreach ($languages as $language) {
            /**
             *
             * @var UserLanguge $language;
             */
            $languagesResource[] = [
                'title' => $language->lang->name,
                'id' => $language->lang->id,
            ];
        }
        return $languagesResource;
    }

    public function getUserType()
    {
        $translations = [
            self::USER_TYPE_PARENT => Yii::t('backend', 'Parent'),
            self::USER_TYPE_PLAYER => Yii::t('backend', 'Player'),
            self::USER_TYPE_TRAINER => Yii::t('backend', 'Trainer'),
            self::USER_TYPE_TALENT => Yii::t('backend', 'Talent'),
            self::USER_TYPE_ACADEMY_ADMIN => Yii::t('backend', 'Academy Admin'),
        ];

        return $translations[$this->user_type];
    }
    public function getUserTypes()
    {
        return [
            self::USER_TYPE_PARENT => Yii::t('backend', 'Parent'),
            self::USER_TYPE_PLAYER => Yii::t('backend', 'Player'),
            self::USER_TYPE_TRAINER => Yii::t('backend', 'Trainer'),
            self::USER_TYPE_TALENT => Yii::t('backend', 'Talent'),
            self::USER_TYPE_ACADEMY_ADMIN => Yii::t('backend', 'Academy Admin'),
            self::USER_TYPE_CUSTOM_ROLE => Yii::t('backend', 'Custom Role'),
        ];
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


    public static function getType($user_type)
    {
        $translations = [
            self::USER_TYPE_PARENT => Yii::t('backend', 'Parent'),
            self::USER_TYPE_PLAYER => Yii::t('backend', 'Player'),
            self::USER_TYPE_TRAINER => Yii::t('backend', 'Trainer'),
            self::USER_TYPE_ACADEMY_ADMIN => Yii::t('backend', 'Academy Admin'),
        ];

        return $translations[$user_type];
    }

    public static function UserRoleName($user_type)
    {

        $translations = [
            self::USER_TYPE_PARENT => Yii::t('backend', 'Parent'),
            self::USER_TYPE_PLAYER => Yii::t('backend', 'Player'),
            self::USER_TYPE_TRAINER => Yii::t('backend', 'Trainer'),
            self::USER_TYPE_ACADEMY_ADMIN => Yii::t('backend', 'Academy Admin'),
        ];

        return $translations[$user_type];
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

    public function getCoachProfile()
    {
        return $this->hasOne(CoachProfile::className(), ['user_id' => 'id']);
    }

    public function getChildren()
    {
        return $this->hasMany(User::className(), ['parent_id' => 'id']);
    }
    public static function getSubscriptionStatuses()
    {
        return [
            0 => 'Inactive',
            1 => 'Active',
            // Add other statuses as needed
        ];
    }

    /**
     * Returns an array of payment statuses.
     * @return array
     */
    public static function getPaymentStatuses()
    {
        return [
            0 => 'Not Paid',
            1 => 'Paid',
            // Add other statuses as needed
        ];
    }
    public function getSubscriptionStatus()
    {
        $statuses = self::getSubscriptionStatuses();
        return isset($statuses[$this->subscription_status]) ? $statuses[$this->subscription_status] : 'Unknown';
    }

    /**
     * Returns the payment status for the user.
     * @return string
     */
    public function getPaymentStatus()
    {
        $statuses = self::getPaymentStatuses();
        return isset($statuses[$this->payment_status]) ? $statuses[$this->payment_status] : 'Unknown';
    }

    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::class, ['parent_id' => 'id']);
    }
    public function getParent()
    {
        return $this->hasOne(User::class, ['id' => 'parent_id']);
    }

    public function getSubscription()
    {
        return $this->hasOne(Subscription::class, ['parent_id' => 'id']);
    }


    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    public function getSubscriptionss()
    {
        return $this->hasMany(Subscription::class, ['parent_id' => 'id']);
    }

    public static function ListRoles($withoutParentRole = false)
    {
        $roles = RbacAuthItem::find()->where([
            'and',
            ['type' => 1],
            ['assignment_category' => NULL],
            ['!=', 'name', 'administrator'],
            ['!=', 'name', 'customRole']
        ]);
        if ($withoutParentRole && !Yii::$app->user->can('administrator')) {
            $roles = $roles->andWhere(['!=', 'name', User::ROLE_PARENT]);
        }
        $roles = $roles->select(['name', 'description'])->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

    public static function ListCustomRoles($limit = null)
    {
        $roles = RbacAuthItem::find()->where([
            'and',
            ['type' => 1],
            ['assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN]
        ])
            ->select(['name', 'description'])->limit($limit)->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

    // Filter custom roles by academy
    public static function ListCustomRolesByAcademy()
    {
        // Get the current academy ID
        $academyId = Yii::$app->controller->academyMainObj->id;

        // Get user IDs belonging to the current academy
        $userIds = \common\models\UserProfile::find()
            ->select('user_id')
            ->where(['academy_id' => $academyId])
            ->column();

        // Get role names assigned to users in the current academy
        $roleNames = \backend\modules\rbac\models\RbacAuthAssignment::find()
            ->select('item_name')
            ->where(['user_id' => $userIds])
            ->column();

        // Get custom roles that are assigned to the current academy
        $roles = RbacAuthItem::find()
            ->where([
                'and',
                ['type' => 1],
                ['assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN],
                ['name' => $roleNames]
            ])
            ->andWhere(['!=', 'name', 'customRole'])
            ->select(['name', 'description'])
            ->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['user_id' => 'id']);
    }

    // parent dropdown while adding player
    public static function getParentOptions()
    {
        $currentAcademyId = Yii::$app->controller->academyMainObj->id;

        // Check if current academy is a sub-branch (if parent_id is set)
        $parentAcademyId = \common\models\Academies::find()
            ->select('parent_id')
            ->where(['id' => $currentAcademyId])
            ->scalar();

        // Determine the academy IDs to filter parents
        $academyIds = $parentAcademyId ? [$currentAcademyId] : \yii\helpers\ArrayHelper::merge(
            [$currentAcademyId], // Main academy
            \common\models\Academies::find()->select('id')->where(['parent_id' => $currentAcademyId])->column() // Sub-branches
        );

        // Direct parent users
        $directParents = User::find()
            ->select(['u.id', 'CONCAT(up.firstname, " (", u.mobile, ")") AS text'])
            ->alias('u')
            ->innerJoin('user_profile up', 'u.id = up.user_id')
            ->where(['u.user_type' => User::USER_TYPE_PARENT, 'up.academy_id' => $academyIds])
            ->asArray()
            ->all();

        return \yii\helpers\ArrayHelper::map($directParents, 'id', 'text');
    }

    public function getCoachFiles()
    {
        return $this->hasMany(CoachFile::class, ['user_id' => 'id']);
    }

    public function getCoachAppointments()
    {
        return $this->hasMany(CoachAppointment::class, ['user_id' => 'id']);
    }
    public function getAcademyId()
    {
        return $this->profile ? $this->profile->academy_id : null;
    }
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }


    public function getTrainerAttendance()
    {
        return $this->hasOne(TrainerAttendance::class, ['user_id' => 'id'])
            ->where(['date' => date('Y-m-d')]); // today's attendance
    }
    public function getPlayerAttendance()
    {
        return $this->hasMany(PlayerAttendance::class, ['player_id' => 'id']);
    }
    public function getSchedulesPlayer()
    {
        return $this->hasMany(SchedulesPlayer::class, ['player_id' => 'id']);
    }
    public function getSubscriptionsDetails()
    {
        return $this->hasMany(SubscriptionDetails::class, ['player_id' => 'id']);
    }

    public function getVitalSigns()
    {
        return $this->hasMany(VitalSign::class, ['player_id' => 'id']);
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

    public function getParentProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'parent_id']);
    }

    public function getDisplayEmail()
    {
        // Check if the email matches 'player_' prefix and ends with '@example.com',
        // or contains '@testzone321'
        if (
            preg_match('/^player_\w+@example\.com$/', $this->email) ||
            preg_match('/@testzone321/i', $this->email) ||
            preg_match('/^user\w+@example\.com$/', $this->email) ||
            preg_match('/^parent\w+@example\.com$/', $this->email)
        ) {
            return '-';
        }
        return $this->email;
    }


    public function getAuthItem()
    {
        return $this->hasOne(RbacAuthItem::class, ['name' => 'item_name']);
    }
    public function getUserRole()
    {
        return $this->hasOne(RbacAuthAssignment::class, ['user_id' => 'id'])->joinWith('itemName');
    }


    public static function ListRolesByAcademy()
    {
        // Get the current academy ID
        $academyId = Yii::$app->controller->academyMainObj->id;

        // Get custom roles that are assigned to the current academy
        $roles = RbacAuthItem::find()
            ->where([
                'and',
                ['type' => 1],
                ['assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN],
                ['academy_id' => $academyId]
            ])
            ->andWhere(['!=', 'name', 'customRole'])
            ->select(['name', 'description'])
            ->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

    public static function getRolesByAcademy($excludeName = null)
    {
        $academyId = Yii::$app->controller->academyMainObj->id;

        $query = RbacAuthItem::find()
            ->where([
                'and',
                ['type' => 1],
                ['assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN],
                ['academy_id' => $academyId]
            ])
            ->andWhere(['!=', 'name', 'customRole']);

        if ($excludeName) {
            $query->andWhere(['!=', 'name', $excludeName]);
        }

        $roles = $query->select(['name', 'description'])->all();
        return ArrayHelper::map($roles, 'name', 'description');
    }
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
