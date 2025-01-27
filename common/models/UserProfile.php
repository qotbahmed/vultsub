<?php

namespace common\models;

use backend\modules\rbac\models\RbacAuthItem;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

// Import PHP's DateTime class


/**
 * This is the base model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $avatar_path
 * @property string $avatar_base_url
 * @property string $locale
 * @property integer $gender
 * @property string $mobile
 * @property string $new_phone
 * @property integer $new_phone_verified
 * @property double $age
 * @property integer $education_level
 * @property double $hour_rate
 * @property integer $preferred_age_from
 * @property integer $preferred_age_from_unit
 * @property integer $preferred_age_to
 * @property integer $preferred_age_to_unit
 * @property integer $from_days
 * @property string $national_id_path
 * @property string $national_id_base_url
 * @property string $permit_path
 * @property string $permit_base_url
 * @property integer $to_days
 * @property integer|null $reward
 * @property integer|null $sport_id
 * @property integer|null $identification_number
 * @property string|null $days
 *
 * @property \common\models\User $user
 */
class UserProfile extends ActiveRecord
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    public $picture;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'picture' => [
                'class' => UploadBehavior::class,
                'attribute' => 'picture',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'gender', 'new_phone_verified', 'to_days', 'from_days', 'preferred_age_to_unit', 'preferred_age_to', 'preferred_age_from_unit',
              'preferred_age_from', 'education_level', 'academy_id', 'reward', 'sport_id', 'subscription_id'], 'integer'],
        

            [['gender'], 'in', 'range' => [NULL, self::GENDER_FEMALE, self::GENDER_MALE]],
            [['firstname', 'middlename', 'lastname', 'avatar_path', 'avatar_base_url', 'mobile', 'national_id_path', 'national_id_base_url', 'permit_path', 'permit_base_url', 'nationality'], 'string', 'max' => 255], 
            ['locale', 'default', 'value' => Yii::$app->language],
            [['new_phone', 'age', 'hour_rate'], 'number'],
            [['dob'], 'date', 'format' => 'php:Y-m-d'], // Add dob
            [['picture', 'avatar_path', 'avatar_base_url', 'national_id_path', 'national_id_base_url', 'permit_path', 'permit_base_url', 'to_days', 'location_id', 'address', 'lat', 'lng', 'days'], 'safe'],
       
         [['identification_number'], 'integer'],
         
        ];
    }



    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('backend', 'User ID'),
            'firstname' => Yii::t('backend', 'Fullname'),
            'address' => Yii::t('common', 'Address'),
            'middlename' => Yii::t('backend', 'Middlename'),
            'lastname' => Yii::t('backend', 'Lastname'),
            'locale' => Yii::t('backend', 'Locale'),
            'picture' => Yii::t('backend', 'Picture'),
            'gender' => Yii::t('backend', 'Gender'),
            'mobile' => Yii::t('backend', 'Mobile'),
            'reward' => Yii::t('backend', 'Reward'),
            'sport_id' => Yii::t('backend', 'sports'),
            'days' => Yii::t('backend', 'Days'),
            'nationality' => Yii::t('backend', 'Nationality'), 
            'dob' => Yii::t('backend', 'Date of Birth'), 
            'Identification Number' => Yii::t('backend','Identification Number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return null|string
     */
    public function getFullName()
    {
        // if ($this->firstname || $this->lastname) {
        //     return implode(' ', [$this->firstname, $this->lastname]);
        // }
        if ($this->firstname) {
            return $this->firstname;
        } else {
            return $this->user ? $this->user->username : null;
        }
        return null;
    }

    /**
     * @param null $default
     * @return bool|null|string
     */
    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . $this->avatar_path)
            : $default;
    }

    public function getNewAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url .'/'. $this->avatar_path)
            : $default;
    }

    public function getNationalIdImage($default = null)
    {
        return $this->national_id_path
            ? Yii::getAlias($this->national_id_base_url  .'/'. $this->national_id_path)
            : $default;
    }

    public function getPermitImage($default = null)
    {
        return $this->permit_path
            ? Yii::getAlias($this->permit_base_url  .'/'. $this->permit_path)
            : $default;
    }


    public function getUserGender()
    {
        if($this->gender == self::GENDER_MALE){
            return Yii::t('common', 'Male');
        } elseif($this->gender == self::GENDER_FEMALE) {
            return Yii::t('common', 'Female');
        }
        else {
            return Yii::t('backend', 'Unknown');
        }
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

    public static function customRolePermissionsList($userId = null)
    {
        // $profile = Yii::$app->user->identity->userProfile;
        $allCategories = RbacAuthItem::findAll(['assignment_category'=>RbacAuthItem::CATEGORY_ASSIGN]);
        $avaiableCategories = [];
        foreach($allCategories as $category) {
            $subCategory = ['name' => $category->name, 'description'=>$category->description];
            $modules = [];
            foreach($category->rbacAuthItemChildren as $module) {
                if(self::checkCustomRolePermmissions($module->child0->name, $module->child0->assignment_category,$userId)) {
                    if($module->child0->assignment_category == RbacAuthItem::MODULE_ASSIGN) {
                        $sub = ['name' => $module->child0->name, 'description'=>$module->child0->description];

                        foreach($module->child0->rbacAuthItemChildren as $contoller) {
                            if(self::checkCustomRolePermmissions($contoller->child0->name, $contoller->child0->assignment_category,$userId)) {
                                $sub['controllers'][] = $contoller->child0;
                            }
                        }
                        array_push($modules, $sub);
                    }elseif($module->child0->assignment_category == RbacAuthItem::ACTION_ASSIGN) {
                        $modules[] = ['name' => str_replace('_','/',$module->child0->name), 'description'=>$module->child0->description];
                    }else{
                        $modules[] = ['name' => $module->child0->name, 'description'=>$module->child0->description];
                    }
                    $subCategory['modules']= $modules;
                }
            }
            if(isset($subCategory['modules'])){
                array_push($avaiableCategories, $subCategory);
            }
        }
        return $avaiableCategories;
    }

    public function checkCustomRolePermmissions($moduleOrControllerValue, $assignment_category, $userId = null)
    {
        $userId = $userId ?: Yii::$app->user->identity->id;

        if ($assignment_category == RbacAuthItem::CONTROLLER_ASSIGN) {
            $result = self::checkControllerInPermissions($moduleOrControllerValue, $userId);
            if ($result) {
                //it's controller
                return true;
            }
        } elseif ($assignment_category == RbacAuthItem::MODULE_ASSIGN) {
            //it's module
            $childs = RbacAuthItem::find()->joinWith('rbacAuthItemChildren0')
                ->where(['parent' => $moduleOrControllerValue])->all();
            $childs = ArrayHelper::getColumn($childs, 'name');
            $resultsCount = 0;
            foreach ($childs as $controller) {
                if (!$resultsCount) {
                    $result = self::checkModuleInPermissions($moduleOrControllerValue, $controller, $userId);
                    $resultsCount = count($result);
                }
            }
            if ($resultsCount) {
                return true;
            }
        } else {
            $permissions = array_keys(Yii::$app->authManager->getPermissionsByUser($userId));

            if (in_array($moduleOrControllerValue, $permissions)) {
                return true;
            }
        }

        return false;
    }

    public function checkModuleInPermissions($moduleOrControllerValue, $controller = null, $userId)
    {
        $permissions = array_keys(Yii::$app->authManager->getPermissionsByUser($userId));

        $controller = $controller ?: $moduleOrControllerValue;
        $result = array_filter(
            $permissions,
            function ($row) use ($controller) {
                return str_contains($row, $controller);
            }
        );
        return $result;
    }

    public function checkControllerInPermissions($controller, $userId)
    {
        $permissions = array_keys(Yii::$app->authManager->getPermissionsByUser($userId));

        $childs = RbacAuthItem::find()->joinWith('rbacAuthItemChildren0')
            ->where(['parent' => $controller])->all();
        if ($childs) {
            $childs = ArrayHelper::getColumn($childs, 'name');

            foreach ($childs as $action) {
                if (in_array($action, $permissions)) {
                    return true;
                }
            }
        } else {
            if (in_array($controller, $permissions)) {
                return true;
            }
        }

        return false;
    }


}