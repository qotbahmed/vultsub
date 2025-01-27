<?php

use common\models\Page;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \common\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('backend', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>

<div class="skill-index card" id="CARD">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

    </p>
    <div class="card-header">
        <h3><?= Yii::t('backend', 'Pages list') ?></h3>
        <?//= Html::a(Yii::t('backend', 'Create Page'), ['create'], ['class' => 'to-modal btn btn-primary']) ?>
        <? //= Html::a('Advance Search', '#', ['class' => 'btn btn-info search-button']) ?>
    </div>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
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
            'template' => '{update}',
            'visibleButtons' => [
            ],

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
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-product']],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,
            'heading' => false,
            'options' => ['class' => false],

        ],

        // set a label for default menu
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
                'filename' => 'List of Page-'. date('d-m-y'),
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
                'filename' => 'List of Page-'. date('d-m-y')
            ],
            GridView::EXCEL => [
                'filename' => 'List of Page-'. date('d-m-y'),
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
