<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SponsorLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$hasFilters = ! empty($_GET);
$this->title = 'Sponsor Log';
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
            Html::tag('i', '', ['class' => 'isax isax-add']) . ' ' . Yii::t('common', 'Create  Sponsor Log' ) ,
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
                'attribute' => 'sponsor_id',
                'label' => 'Sponsor',
                'value' => function($model){
                    return $model->sponsors->title;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\Sponsors::find()->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Sponsors', 'id' => 'grid-sponsor-log-search-sponsor_id']
            ],
        'amount',
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-sponsor-log']],
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
