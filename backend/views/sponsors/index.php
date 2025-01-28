<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SponsorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$hasFilters = ! empty($_GET);
$this->title = Yii::t('backend','Sponsors');
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
            Html::tag('i', '', ['class' => 'isax isax-add']) . ' ' . Yii::t('backend', 'Create Sponsors' ) ,
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
        'title',
        [
            'label' => Yii::t('backend', 'Image'),
            'format' => 'html',
            'value' => function ($model) {
                return Html::img($model->getImage(), ['width' => '40px', 'height' => '40px']);
            },
            'filter' => false,
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            "width"=>"20%",
            "template"=>' {view} {delete}'//{delete}
        ],
    ]; 
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'options' => [
            'class' => ['gridview', 'table-responsive'],
        ],
        'layout' => "{items}\n{pager}",

        'tableOptions' => [
            'class' => ['table', 'text-nowrap', 'mb-0'],
        ],

        // 'pjax' => true,
        // 'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-product']],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,
            'heading' => false,
            'options' => ['class' => false],

        ],

        // set a label for default menu
        'export' => [
            'label' => Yii::t('backend','Page'),
            'fontAwesome' => true,
        ],
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'filename' => 'List of Customers-'. date('d-m-y'),

                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => Yii::t('backend','Full'),
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">'.Yii::t('backend','Export All Data').'</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                ]
            ]),
        ],
        'exportConfig' => [
            GridView::CSV => [
                'filename' => 'List of Customers-'. date('d-m-y')
            ],
            GridView::EXCEL => [
                'filename' => 'List of Customers-'. date('d-m-y'),
            ],

        ],
    ]); ?>


    <div class="col-md-12 text-center" style="display: flex; justify-content: center;">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination'=>$dataProvider->pagination,
            'options' => ['class' => 'pagination']
        ]) ?>
    </div>
</div>

    <div class="card-footer">
        <?php echo getDataProviderSummary($dataProvider) ?>
    </div>
</div>



