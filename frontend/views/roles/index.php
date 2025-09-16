<?php

use yii\rbac\Item;
use yii\helpers\Html;
use kartik\grid\GridView;
use common\grid\EnumColumn;
use backend\modules\rbac\models\RbacAuthItem;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'الصلاحيات';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-auth-item-index">

    <p>
        <?php echo Html::a('إضافة صلاحيات جديدة', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                    foreach($model->rbacAuthItemChildren as $action){
                        $permissions[] = $action->child0->description;
                    }
                    return $permissions ? implode(", ", $permissions) : '';
                },
            ],


            [
                'attribute' => 'assignment_category',
                'value' => function($model){
                    return  RbacAuthItem::getAssignCategoriesList()[$model->assignment_category];
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['' => Yii::t('common', 'Select')] + RbacAuthItem::getAssignCategoriesList(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Cities', 'id' => 'grid-districts-search-city_id']
            ],

            ['class' => 'yii\grid\ActionColumn',],
        ],
    ]); ?>
</div>
