<?php

use backend\modules\rbac\models\RbacAuthItem;
use common\grid\EnumColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\rbac\Item;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'صلاحيات المديرين';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-auth-item-index">

    <p>
        <?php echo Html::a('إضافة صلاحية جديدة', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'label' => 'الصلاحية',
                'attribute' => 'description',
            ],
            [
                'label' => 'الصلاحيات الفرعية',
                'value' => function ($model){
                    $permissions = [];
                    RbacAuthItem::getAllChildsValue($model,$permissions);
                    // foreach($model->rbacAuthItemChildren as $action){
                    //     if($action->child0->name == 'customRole')
                    //         continue;
                    //     if($action->child0->assignment_category == RbacAuthItem::CUSTOM_ROLE_ASSIGN){
                    //         foreach($action->child0->rbacAuthItemChildren as $parentRoleAction){
                    //             if($parentRoleAction->child0->name == 'customRole')
                    //                 continue;
                    //             $permissions[] = $parentRoleAction->child0->description;
                    //         }
                    //     }else{
                    //         $permissions[] = $action->child0->description;
                    //     }
                        
                    // }
                    return $permissions ? implode(", ", $permissions) : '';
                },
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>
</div>
