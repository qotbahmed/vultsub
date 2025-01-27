<?php

use common\grid\EnumColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\rbac\Item;
use rmrevin\yii\fontawesome\FAS;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-auth-item-index card" id="CARD">
    <div class="card-header">
        <?php echo Html::a(FAS::icon('user-plus').' '.Yii::t('backend', 'Add New {modelClass}', [
            'modelClass' => 'Assignment',
        ]), ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return '<strong>' . $model->title . '</strong>';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'visibleButtons' => [

                'delete' => function ($model) {
                    return !Skill::isDeletable($model->id);
                },

            ],

        ],


    ];
    ?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'options' => [
            'class' => ['gridview', 'table-responsive'],
        ],
        'tableOptions' => [
            'class' => ['table', 'text-nowrap', 'table-striped', 'table-bordered', 'mb-0'],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'class' => EnumColumn::class,
                'attribute' => 'type',
                'enum' => [
                        Item::TYPE_ROLE => 'role',
                        Item::TYPE_PERMISSION => 'permission',
                ]
            ],
            'description:ntext',
            'rule_name',
            'data',
            // 'created_at',
            // 'updated_at',

            ['class' => \common\widgets\ActionColumn::class],
        ],
    ]); ?>


    <div class="card-footer">
        <?php echo getDataProviderSummary($dataProvider) ?>
    </div>
</div>




