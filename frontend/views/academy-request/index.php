<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'طلبات الأكاديميات';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .admin-header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #ff6b35 100%); }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-approved { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        .status-expired { background-color: #6c757d; color: #fff; }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-building me-2"></i>
                        إدارة طلبات الأكاديميات
                    </h2>
                    <p class="mb-0">مراجعة واعتماد طلبات التسجيل الجديدة</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2">
                        <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-outline-light">
                            <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
                        </a>
                        <a href="<?= Url::to(['/site/logout']) ?>" class="btn btn-outline-light">
                            <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                        <h4 class="fw-bold text-warning"><?= $searchModel->search(['AcademyRequestSearch' => ['status' => 'pending']])->getTotalCount() ?></h4>
                        <p class="text-muted mb-0">في الانتظار</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h4 class="fw-bold text-success"><?= $searchModel->search(['AcademyRequestSearch' => ['status' => 'approved']])->getTotalCount() ?></h4>
                        <p class="text-muted mb-0">معتمدة</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                        <h4 class="fw-bold text-danger"><?= $searchModel->search(['AcademyRequestSearch' => ['status' => 'rejected']])->getTotalCount() ?></h4>
                        <p class="text-muted mb-0">مرفوضة</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-list fa-2x text-primary mb-2"></i>
                        <h4 class="fw-bold text-primary"><?= $dataProvider->getTotalCount() ?></h4>
                        <p class="text-muted mb-0">إجمالي الطلبات</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    قائمة طلبات الأكاديميات
                </h5>
            </div>
            <div class="card-body p-0">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover mb-0'],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'label' => 'رقم الطلب',
                            'headerOptions' => ['style' => 'width: 80px;'],
                        ],
                        [
                            'attribute' => 'academy_name',
                            'label' => 'اسم الأكاديمية',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->academy_name, ['view', 'id' => $model->id], [
                                    'class' => 'text-decoration-none fw-bold'
                                ]);
                            },
                        ],
                        [
                            'attribute' => 'manager_name',
                            'label' => 'اسم المدير',
                        ],
                        [
                            'attribute' => 'email',
                            'label' => 'البريد الإلكتروني',
                        ],
                        [
                            'attribute' => 'phone',
                            'label' => 'رقم الهاتف',
                        ],
                        [
                            'attribute' => 'city',
                            'label' => 'المدينة',
                        ],
                        [
                            'attribute' => 'status',
                            'label' => 'الحالة',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $statusClasses = [
                                    'pending' => 'status-pending',
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected',
                                    'expired' => 'status-expired',
                                ];
                                $statusLabels = [
                                    'pending' => 'في الانتظار',
                                    'approved' => 'معتمد',
                                    'rejected' => 'مرفوض',
                                    'expired' => 'منتهي الصلاحية',
                                ];
                                $class = $statusClasses[$model->status] ?? 'status-pending';
                                $label = $statusLabels[$model->status] ?? $model->status;
                                return Html::tag('span', $label, ['class' => "badge {$class}"]);
                            },
                            'filter' => [
                                'pending' => 'في الانتظار',
                                'approved' => 'معتمد',
                                'rejected' => 'مرفوض',
                                'expired' => 'منتهي الصلاحية',
                            ],
                        ],
                        [
                            'attribute' => 'requested_at',
                            'label' => 'تاريخ الطلب',
                            'format' => 'datetime',
                            'headerOptions' => ['style' => 'width: 150px;'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'الإجراءات',
                            'template' => '{view} {approve} {reject}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], [
                                        'class' => 'btn btn-sm btn-outline-primary',
                                        'title' => 'عرض التفاصيل'
                                    ]);
                                },
                                'approve' => function ($url, $model, $key) {
                                    if ($model->status === 'pending') {
                                        return Html::a('<i class="fas fa-check"></i>', ['approve', 'id' => $model->id], [
                                            'class' => 'btn btn-sm btn-success',
                                            'title' => 'اعتماد الطلب',
                                            'data' => [
                                                'confirm' => 'هل أنت متأكد من اعتماد هذا الطلب؟',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                    return '';
                                },
                                'reject' => function ($url, $model, $key) {
                                    if ($model->status === 'pending') {
                                        return Html::a('<i class="fas fa-times"></i>', ['reject', 'id' => $model->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'رفض الطلب',
                                            'data' => [
                                                'confirm' => 'هل أنت متأكد من رفض هذا الطلب؟',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                    return '';
                                },
                            ],
                            'headerOptions' => ['style' => 'width: 120px;'],
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
