<?php

use common\models\SponsorLog;
use common\models\Sponsors;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;

// Total statistics
$totalDonations = SponsorLog::find()->sum('amount');
$totalSponsors = Sponsors::find()->count();
$totalRequests = 12345; // Example static data, replace with real query

// Get latest donations
$recentDonations = SponsorLog::find()->orderBy(['created_at' => SORT_DESC])->limit(5)->all();

// Dummy monthly data for Chart.js (Replace this with DB data)
$months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
$donationData = [1000, 1500, 2000, 2500, 3000, 4000, 3200, 2800, 3500, 3800, 4100, 4500];
$requestData = [800, 1200, 1700, 2200, 2600, 3500, 3000, 2500, 2900, 3100, 3700, 3900];

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);




?>
<style>
    .stat-card {
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .text-success {
        color: #28a745 !important;
    }

</style>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            👋 <?= Yii::t('backend', 'Welcome Back, ') . Yii::$app->user->identity->userProfile->getFullName() ?></h2>
        <span class="text-muted"><?= Yii::t('backend', 'Latest Statistics') ?></span>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card bg-light p-3 text-center">
                <h6 class="text-muted"><?= Yii::t('backend', 'Total Donations') ?></h6>
                <h3 class="fw-bold"><?= number_format($totalDonations) ?> رس</h3>
                <span class="text-success">⬆ 50% <?= Yii::t('backend', 'this week') ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-light p-3 text-center">
                <h6 class="text-muted"><?= Yii::t('backend', 'Total Sponsors') ?></h6>
                <h3 class="fw-bold"><?= number_format($totalSponsors) ?></h3>
                <span class="text-success">⬆ 50% <?= Yii::t('backend', 'this week') ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-light p-3 text-center">
                <h6 class="text-muted"><?= Yii::t('backend', 'Donations Requests') ?></h6>
                <h3 class="fw-bold"><?= number_format(5436) ?> رس</h3>
                <span class="text-success">⬆ 50% <?= Yii::t('backend', 'this week') ?></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-light p-3 text-center">
                <h6 class="text-muted"><?= Yii::t('backend', 'Total Requests') ?></h6>
                <h3 class="fw-bold"><?= number_format($totalRequests) ?></h3>
                <span class="text-success">⬆ 50% <?= Yii::t('backend', 'this week') ?></span>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="card mt-4 p-4">
        <h5><?= Yii::t('backend', 'Total Donations Over the Year') ?></h5>
        <canvas id="donationChart"></canvas>
    </div>

    <!-- Recent Donations Table -->
    <div class="card mt-4 p-4">
        <h5><?= Yii::t('backend', 'Latest Donations') ?></h5>
        <?= GridView::widget([
            'dataProvider' => new yii\data\ArrayDataProvider([
                'allModels' => $recentDonations,
                'pagination' => ['pageSize' => 5]
            ]),
            'columns' => [
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

                ], [
                    'attribute' => 'amount',
                    'filter' => false,
                    'value' => function ($model) {
                        return $model->amount . ' ر.س ';
                    },
                ], [
                    'label' => Yii::t('backend', 'Donation time and date'),
                    'filter' => false,
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->created_at, 'dd/MM/yyyy hh:mm a');
                    },
                ],
            ]
        ]); ?>
    </div>

