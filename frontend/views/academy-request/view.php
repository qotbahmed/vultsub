<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'تفاصيل طلب الأكاديمية: ' . $model->academy_name;
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
        .info-card { border-left: 4px solid #007bff; }
        .sports-badge { margin: 2px; }
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
                        تفاصيل طلب الأكاديمية
                    </h2>
                    <p class="mb-0"><?= Html::encode($model->academy_name) ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2">
                        <a href="<?= Url::to(['/academy-request/index']) ?>" class="btn btn-outline-light">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
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
        <!-- Status and Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-2">حالة الطلب:</h5>
                                <?php
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
                                ?>
                                <span class="badge <?= $class ?> fs-6"><?= $label ?></span>
                            </div>
                            <div class="col-md-6 text-end">
                                <?php if ($model->status === 'pending'): ?>
                                    <div class="btn-group">
                                        <?= Html::a('<i class="fas fa-check me-2"></i>اعتماد الطلب', ['approve', 'id' => $model->id], [
                                            'class' => 'btn btn-success',
                                            'data' => [
                                                'confirm' => 'هل أنت متأكد من اعتماد هذا الطلب؟',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-times me-2"></i>رفض الطلب', ['reject', 'id' => $model->id], [
                                            'class' => 'btn btn-danger',
                                            'data' => [
                                                'confirm' => 'هل أنت متأكد من رفض هذا الطلب؟',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academy Information -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            المعلومات الأساسية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">اسم الأكاديمية:</label>
                                <p class="mb-0"><?= Html::encode($model->academy_name) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">اسم المدير:</label>
                                <p class="mb-0"><?= Html::encode($model->manager_name) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">البريد الإلكتروني:</label>
                                <p class="mb-0">
                                    <a href="mailto:<?= Html::encode($model->email) ?>" class="text-decoration-none">
                                        <?= Html::encode($model->email) ?>
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">رقم الهاتف:</label>
                                <p class="mb-0">
                                    <a href="tel:<?= Html::encode($model->phone) ?>" class="text-decoration-none">
                                        <?= Html::encode($model->phone) ?>
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">المدينة:</label>
                                <p class="mb-0"><?= Html::encode($model->city) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-muted">عدد الفروع:</label>
                                <p class="mb-0"><?= $model->branches_count ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address and Description -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            العنوان والوصف
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold text-muted">العنوان:</label>
                            <p class="mb-0"><?= Html::encode($model->address) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-muted">الوصف:</label>
                            <p class="mb-0"><?= Html::encode($model->description) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Sports -->
                <?php if ($model->sports): ?>
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-futbol me-2"></i>
                            الرياضات المقدمة
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $sports = explode(',', $model->sports);
                        foreach ($sports as $sport):
                        ?>
                            <span class="badge bg-info sports-badge"><?= Html::encode(trim($sport)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Notes -->
                <?php if ($model->notes): ?>
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-sticky-note me-2"></i>
                            الملاحظات
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= Html::encode($model->notes) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Request Timeline -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            الجدول الزمني
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">تم إرسال الطلب</h6>
                                    <p class="text-muted small mb-0"><?= date('Y-m-d H:i:s', $model->requested_at) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($model->approved_at): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">تم اعتماد الطلب</h6>
                                    <p class="text-muted small mb-0"><?= date('Y-m-d H:i:s', $model->approved_at) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($model->rejected_at): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">تم رفض الطلب</h6>
                                    <p class="text-muted small mb-0"><?= date('Y-m-d H:i:s', $model->rejected_at) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            الإجراءات السريعة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?= Html::a('<i class="fas fa-edit me-2"></i>تعديل الطلب', ['update', 'id' => $model->id], [
                                'class' => 'btn btn-outline-primary'
                            ]) ?>
                            <?= Html::a('<i class="fas fa-print me-2"></i>طباعة الطلب', ['#'], [
                                'class' => 'btn btn-outline-secondary',
                                'onclick' => 'window.print(); return false;'
                            ]) ?>
                            <?= Html::a('<i class="fas fa-envelope me-2"></i>إرسال إيميل', ['#'], [
                                'class' => 'btn btn-outline-info'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-marker {
            position: absolute;
            left: -35px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .timeline-content h6 {
            margin-bottom: 5px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
