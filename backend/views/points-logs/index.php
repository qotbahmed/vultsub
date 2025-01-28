<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\\PointsLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$hasFilters = ! empty($_GET);
$this->title = 'Points Logs';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
    <div class="section_header_right">
        <h4 class="mb-0">
            <?=  $this->title ?>
        </h4>
    </div>
    <div class="mb-0 d-inline-flex gap-2">
        <a class="btn filter_toggler <?=  $hasFilters ? '' : 'collapsed' ?>" data-toggle="collapse" href="#collapseFilters" role="button" aria-expanded="false" aria-controls="collapseExample">
            <span class="isax icon isax-filter-remove"></span>
        </a>
        <?=  Html::a(
            Html::tag('i', '', ['class' => 'isax isax-add']) . ' ' . Yii::t('common', 'Create  Points Logs' ) ,
            ['create'],
            ['class' => 'btn btn-secondary']
        ) ?>
    </div>
</div>




<div class="py-3">

   <!-- Filters Toolbar -->
    <div id="collapseFilters" class="collapse <?=  $hasFilters ? 'show' : '' ?>">
        <div class="section_toolbar">
            <?=  $this->render('_search', ['model' => $searchModel ,'hasFilters'=>$hasFilters]); ?>
        </div>
    </div>
    <!-- End Filters Toolbar -->

    
<div>
    
    <?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
                'attribute' => 'user_id',
                'label' => 'User',
                'value' => function($model){
                    return $model->user->id;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-points-logs-search-user_id']
            ],
        'user_name',
        'user_mobile',
        'points_num',
        'type',
        'page_num',
        'time:datetime',
        [
            'class' => 'kartik\grid\ActionColumn',
            "width"=>"20%",
            "template"=>'{update} {view} '//{delete}
        ],
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'options' => ['class' => 'gridview table-responsive'],
        'tableOptions' => ['class' => 'table text-nowrap mb-0'],
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-points-logs']],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,
            'heading' => false ,
        ],
        // set a label for default menu
        'export' => [
            'label' => 'Page',
            'fontAwesome' => true,
            'options' => ['class' => false],
        ],
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                ]
            ]) ,
        ],
        'exportConfig' => [
            GridView::CSV => ['label' => 'Save as CSV'],
            GridView::EXCEL => [ ],

        ],
    ]); ?>

</div>
</div>
