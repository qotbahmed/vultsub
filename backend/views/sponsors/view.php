<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Sponsors */


$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'إدارة الرعاة'), 'url' => ['index']];

$lastDonation = !empty($model->logs) ? end($model->logs) : null;
$lastDonationDate = $lastDonation ? $lastDonation->created_at : null;
$lastDonationAmount = $lastDonation ? $lastDonation->amount : 0;

$totalDonations = array_sum(array_column($model->logs, 'amount'));

?>
<div class="sponsor-info border-bottom pb-3 mb-4">
    <h4 class="text-primary m-0">
        <?= Yii::t('backend', 'شركة الراعي') ?>: <?= Html::encode($this->title) ?>
    </h4>
    <div class="text-muted small mt-2">
        <span class="mx-2">•</span>
        <span><i class="fas fa-money-bill-wave"></i> <?= Yii::t('backend', 'آخر مبلغ متبرع به') ?>:
                <span class="text-danger font-weight-bold"><?= number_format($lastDonationAmount) ?> <?= Yii::t('backend', 'ريال سعودي') ?></span>
            </span>
        <span class="mx-2">•</span>
        <span><i class="fas fa-hand-holding-usd"></i> <?= Yii::t('backend', 'إجمالي التبرعات') ?>:
                <span class="text-success font-weight-bold"><?= number_format($totalDonations) ?> <?= Yii::t('backend', 'ريال سعودي') ?></span>
            </span>
        <span class="mx-2">•</span>

        <span><i class="fas fa-calendar-alt"></i> <?= Yii::t('backend', 'تاريخ الإنضمام') ?>: <?= Yii::$app->formatter->asDate($model->created_at, 'dd MMMM yyyy') ?></span>
        <span class="mx-2">•</span>
        <span><i class="fas fa-calendar-check"></i> <?= Yii::t('backend', 'تاريخ آخر تبرع') ?>:
                <?= $lastDonationDate ? Yii::$app->formatter->asDate($lastDonationDate, 'dd MMMM yyyy') : Yii::t('backend', 'لم يتم التبرع بعد') ?>
            </span>

    </div>
</div>

<!-- Sponsor History Timeline -->
<div class="timeline mt-4">
    <h5 class="text-secondary mb-3">
        <?= Yii::t('backend', 'تاريخ الراعي') ?>
    </h5>
    <div class="d-flex justify-content-end mb-2">
        <a href="#" class="text-primary small"> <?= Yii::t('backend', 'عرض من الأحدث للأقدم') ?> </a>
    </div>
    <ul class="list-unstyled timeline-list">
        <?php foreach ($model->logs as $log): ?>
            <li class="timeline-item">
                <div class="timeline-content">
                    <p class="mb-1">
                        <?= Yii::t('backend', 'تمت إضافة تبرع بمبلغ') ?>
                        <span class="text-danger font-weight-bold"> <?= number_format($log->amount) ?> </span>
                        <?= Yii::t('backend', 'ريال سعودي وإضافة') ?>
                        <span class="text-primary font-weight-bold"> <?= number_format(0) ?> </span>
                        <?= Yii::t('backend', 'نقطة لرصيده بالمنصة') ?>
                    </p>
                    <span class="timeline-date">
                            <?= Yii::$app->formatter->asDate($log->created_at, 'dd/MM/yyyy') ?>
                        </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>
    body {
        font-family: 'Tajawal', sans-serif;
    }

    .sponsors-view {
        max-width: 900px;
        margin: auto;
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .sponsor-info h4 {
        font-size: 1.5rem;
        color: #007bff;
    }

    .sponsor-info .text-muted {
        font-size: 0.9rem;
    }

    .timeline {
        margin-top: 20px;
    }

    .timeline h5 {
        font-size: 1.2rem;
        color: #6c757d;
    }

    .timeline-list {
        position: relative;
        padding-left: 20px;
        list-style: none;
    }

    .timeline-list::before {
        content: '';
        position: absolute;
        top: 0;
        left: 7px;
        width: 3px;
        height: 100%;
        background: #007bff;
    }

    .timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 15px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        top: 5px;
        left: -3px;
        width: 12px;
        height: 12px;
        background: #fff;
        border: 3px solid #007bff;
        border-radius: 50%;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 6px;
        font-size: 0.95rem;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .timeline-date {
        font-size: 0.85rem;
        color: #6c757d;
        background: #e9ecef;
        padding: 4px 10px;
        border-radius: 4px;
    }
</style>
