<?php

use common\models\SponsorLog;
use common\models\Sponsors;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SponsorLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$hasFilters = !empty($_GET);
$this->title = Yii::t('backend', 'Sponsor Logs');
$this->params['breadcrumbs'][] = $this->title;

// Total sponsors count
$totalSponsors = Sponsors::find()->count();

// Total sponsorship amount
$totalSponsorshipAmount = SponsorLog::find()->sum('amount');

// Get last month's total sponsorship amount for comparison
$lastMonthSponsorshipAmount = SponsorLog::find()
    ->where(['between', 'created_at', date('Y-m-01', strtotime('-1 month')), date('Y-m-t', strtotime('-1 month'))])
    ->sum('amount');

// Calculate the percentage change from last month
$percentageChange = $lastMonthSponsorshipAmount > 0
    ? round((($totalSponsorshipAmount - $lastMonthSponsorshipAmount) / $lastMonthSponsorshipAmount) * 100, 2)
    : 0;

?>


<style>
    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        border: 2px solid #007bff; /* Blue border */
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .stat-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: #000;
    }

    .stat-compare {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }

    .stat-percent {
        font-weight: bold;
        margin-left: 5px;
    }

    .text-success {
        color: #28a745;
    }

</style>


<div class="d-flex align-items-center flex-wrap justify-content-between mb-4">
    <div>
        <h4>
            <?= $this->title ?>
        </h4>
    </div>
    <div class="mb-0 d-inline-flex gap-2">
        <a class="btn filter_toggler <?= $hasFilters ? '' : 'collapsed' ?>" data-toggle="collapse"
           href="#collapseFilters" role="button" aria-expanded="false" aria-controls="collapseExample">
            <span class="isax icon isax-filter-remove"></span>
        </a>
        <?= Html::a(
            Html::tag('i', '', ['class' => 'isax isax-add']) . ' ' . Yii::t('backend', 'Create Sponsor Log'),
            ['create'],
            ['class' => 'btn btn-primary text-white']
        ) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-title"><?= Yii::t('backend', 'Total Donations') ?></span>
                <span class="stat-value"><?= number_format($totalSponsorshipAmount) ?></span>
                <span class="stat-compare">
                    <?= Yii::t('backend', 'Comparison with last month') ?>
                    <span class="stat-percent text-success">
                        <i class="isax isax-arrow-up"></i> <?= $percentageChange ?>%
                    </span>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-title"><?= Yii::t('backend', 'Total Sponsors') ?></span>
                <span class="stat-value"><?= number_format($totalSponsors) ?></span>
                <span class="stat-compare">
                    <?= Yii::t('backend', 'Comparison with last month') ?>
                    <span class="stat-percent text-success">
                        <i class="isax isax-arrow-up"></i> <?= $percentageChange ?>%
                    </span>
                </span>
            </div>
        </div>
    </div>
</div>






<div class="card-border bg-gray mt-4 p-3">

    <!-- Filters Toolbar -->
    <div id="collapseFilters" class="collapse <?= $hasFilters ? 'show' : '' ?>">
        <div class="section_toolbar">
            <?= $this->render('_search', ['model' => $searchModel, 'hasFilters' => $hasFilters]); ?>
        </div>
    </div>
    <!-- End Filters Toolbar -->


    <div>

        <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'label' => Yii::t('backend', 'Donation ID'),
                'value' => function ($model) {
                    return $model->id;
                },
            ],
            [
                'attribute' => 'sponsor_id',
                'label' => Yii::t('backend', 'Sponsor Name'),
                'value' => function ($model) {
                    return $model->sponsor->title;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\Sponsors::find()->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Sponsors', 'id' => 'grid-sponsor-log-search-sponsor_id']
            ],
            [
                'attribute' => 'amount',
                'filter' => false,
                'value' => function ($model) {
                    return $model->amount.' ر.س ';
                },
            ],
            [
                'label' => Yii::t('backend', 'Donation time and date'),
                'filter' => false,
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at, 'dd/MM/yyyy hh:mm a');
                },
            ],
            [
                'label' => Yii::t('backend', 'Total donations in the system'),
                'filter' => false,
                'value' => function ($model) {
                    if ($model->sponsor && $model->sponsor->logs) {
                        $amounts = array_column($model->sponsor->logs, 'amount');
                        return array_sum($amounts) .' ر.س ';
                    }

                    return 0;
                }
            ],

        ];
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => null,
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



