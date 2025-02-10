<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'FAQs');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>

<div class="faq-index custom-card ">


    <div class="card-header">
        <h3><?= $this->title = Yii::t('backend', 'FAQs'); ?></h3>


        <p>
            <?= Html::a(Yii::t('backend', 'Create FAQs'), ['create'], ['class' => 'to-modal btn btn-primary']) ?>
        </p>
        <div class="search-form" style="display:none">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

    <div class="card-body">
        <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            'question',
            [
                'attribute' => 'answer',
                'value' => function ($model) {
                    return StringHelper::truncate($model->answer, 80);
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getStatuses()[$model->status];
                },

                'filter' => Html::activeDropDownList($searchModel,
                    'status',
                    (statuses()), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'Select Status')]),
            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->category->name;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'category_id',
                    ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'),
                    ['class' => 'form-control', 'prompt' => Yii::t('backend', 'Select Category')]
                ),
            ],
            [
                    'header'=>Yii::t('backend','-'),
                    'class' => 'demi\sort\SortColumn',
                    'headerOptions' => ['class' => 'SortingColC']

            ],


            [
                'class' => 'kartik\grid\ActionColumn',
                "width" => "20%",
                "template" => '{update} {delete} '//{delete} {view}
            ],
        ];
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'class' => ['gridview', 'table-responsive'],
            ],
            'tableOptions' => [
                'class' => ['table', 'text-nowrap', 'mb-0'],
            ],
            'columns' => $gridColumn,

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
                    'filename' => 'List of Faqs-'. date('d-m-y'),
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
                    'filename' => 'List of Faqs-'. date('d-m-y')
                ],
                GridView::EXCEL => [
                    'filename' => 'List of Faqs-'. date('d-m-y'),
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
</div>
