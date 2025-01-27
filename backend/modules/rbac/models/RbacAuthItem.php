<?php

namespace backend\modules\rbac\models;

use Yii;

/**
 * This is the model class for table "rbac_auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property integer $assignment_category
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property RbacAuthAssignment[] $rbacAuthAssignments
 * @property RbacAuthRule $ruleName
 * @property RbacAuthItemChild[] $rbacAuthItemChildren
 * @property RbacAuthItemChild[] $rbacAuthItemChildren0
 * @property RbacAuthItem[] $children
 * @property RbacAuthItem[] $parents
 */
class RbacAuthItem extends \yii\db\ActiveRecord
{
    const CATEGORY_ASSIGN = 1;
    const MODULE_ASSIGN = 2;
    const CONTROLLER_ASSIGN = 3;
    const ACTION_ASSIGN = 4;
    const CUSTOM_ROLE_ASSIGN = 5;

    public $isParent, $itemParent, $subRoles = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at','assignment_category'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => RbacAuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
            [['isParent','itemParent','subRoles'],'safe'],
            [['name'],'unique'],
            [['name','description'],'string','min'=>2, 'max'=>64],
            [['name','description'],'required'],
            [['description'],'unique','on'=>'addCustomRole'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacAuthAssignments()
    {
        return $this->hasMany(RbacAuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(RbacAuthRule::class, ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacAuthItemChildren()
    {
        return $this->hasMany(RbacAuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacAuthItemChildren0()
    {
        return $this->hasMany(RbacAuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(RbacAuthItem::class, ['name' => 'child'])->viaTable('rbac_auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(RbacAuthItem::class, ['name' => 'parent'])->viaTable('rbac_auth_item_child', ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCustomRoleMainChild()
    {
        return $this->hasOne(RbacAuthItemChild::class, ['parent' => 'name'])
            ->joinWith(['child0'])
            ->andOnCondition(['type'=>1,'assignment_category'=>self::CUSTOM_ROLE_ASSIGN]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleMainParent()
    {
        return $this->hasOne(RbacAuthItemChild::class, ['child' => 'name'])
            ->joinWith('parent0')->andOnCondition(['type'=>1])
            ->andOnCondition(['or',['assignment_category'=> self::CONTROLLER_ASSIGN],
                ['assignment_category'=> self::CATEGORY_ASSIGN],
                ['assignment_category'=> self::MODULE_ASSIGN]]);
    }

    public static function getAssignCategoriesList()
    {
        return [
            self::CATEGORY_ASSIGN => 'category',
            self::MODULE_ASSIGN => 'module',
            self::CONTROLLER_ASSIGN => 'controller',
            self::ACTION_ASSIGN => 'action',
        ];
    }

    public static function getAllChildsKey($permission, &$data)
    {
        if($permission->assignment_category == RbacAuthItem::CUSTOM_ROLE_ASSIGN){
            foreach($permission->rbacAuthItemChildren as $parentRoleAction){
                if($parentRoleAction->child0->name == 'customRole'){
                    continue;
                }elseif($parentRoleAction->child0->rbacAuthItemChildren){
                    self::getAllChildsKey($parentRoleAction->child0, $data);
                }else{
                    $data[] = $parentRoleAction->child0->name;
                }
            }
        }else{
            $data[] = $permission->name;
        }
        return $data;
    }

    public static function getAllChildsValue($permission, &$data)
    {
        if($permission->assignment_category == RbacAuthItem::CUSTOM_ROLE_ASSIGN){
            foreach($permission->rbacAuthItemChildren as $parentRoleAction){
                if($parentRoleAction->child0->name == 'customRole'){
                    continue;
                }elseif($parentRoleAction->child0->rbacAuthItemChildren){
                    self::getAllChildsValue($parentRoleAction->child0, $data);
                }else{
                    $data[] = $parentRoleAction->child0->description;
                }
            }
        }else{
            $data[] = $permission->description;
        }
        return $data;
    }

    public static function getPermissionCategory($permissionName, &$data)
    {
        $permission = self::findOne(['name'=>$permissionName]);
        foreach($permission->rbacAuthItemChildren0 as $parentRoleAction){
            if($parentRoleAction->parent0->rbacAuthItemChildren0
                && $parentRoleAction->parent0->assignment_category != self::CATEGORY_ASSIGN){

                self::getPermissionCategory($parentRoleAction->parent0, $data);
            }else{
                if($parentRoleAction->parent0->assignment_category != self::CUSTOM_ROLE_ASSIGN){
                    $data[] = $parentRoleAction->parent0;
                }
            }
        }

        return $data;
    }

}
