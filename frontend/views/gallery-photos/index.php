<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\GalleryPhotosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Home Page Slider');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>

<div class="gallery-photos-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Add slide'), ['create'], ['class' => 'to-modal btn btn-primary']) ?>
        <? //= Html::a(Yii::t('backend', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
        <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
        <?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
//        [
//                'attribute' => 'gallery_id',
//                'label' => Yii::t('backend', 'Gallery'),
//                'value' => function($model){
//                    return $model->gallery->title;
//                },
//                'filterType' => GridView::FILTER_SELECT2,
//                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\Gallery::find()->asArray()->all(), 'id', 'title'),
//                'filterWidgetOptions' => [
//                    'pluginOptions' => ['allowClear' => true],
//                ],
//                'filterInputOptions' => ['placeholder' => 'Gallery', 'id' => 'grid-gallery-photos-search-gallery_id']
//            ],
        'title',
        'header_one',
        [
            'class' => 'demi\sort\SortColumn',
            'action' => 'change-sort', // optional
            'headerOptions'=>['class'=>"SortingColC"]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            "width"=>"20%",
            "template"=>'{update} {view} '//{delete}
        ],
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-gallery-photos']],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        // set a label for default menu
        'export' => [
            'label' => 'Page',
            'fontAwesome' => true,
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
