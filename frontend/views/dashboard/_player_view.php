<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Check if model exists
if (!$model) {
    echo '<div class="alert alert-danger">';
    echo '<h5><i class="fas fa-exclamation-triangle me-2"></i>خطأ في تحميل البيانات</h5>';
    echo '<p>' . (isset($error) ? Html::encode($error) : 'اللاعب غير موجود أو لا يمكن الوصول إليه.') . '</p>';
    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>';
    echo '</div>';
    return;
}

$age = $model->date_of_birth ? floor((time() - strtotime($model->date_of_birth)) / 31556926) : 'غير محدد';
?>

<div class="player-details">
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="player-avatar-large mb-3">
                <?= strtoupper(substr($model->name, 0, 1)) ?>
            </div>
            <h4 class="mb-1"><?= Html::encode($model->name) ?></h4>
            <p class="text-muted mb-0"><?= Html::encode($model->sport) ?></p>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            البريد الإلكتروني
                        </h6>
                        <p class="mb-0"><?= Html::encode($model->email) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            رقم الهاتف
                        </h6>
                        <p class="mb-0"><?= Html::encode($model->phone) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-calendar me-2 text-primary"></i>
                            تاريخ الميلاد
                        </h6>
                        <p class="mb-0"><?= Html::encode($model->date_of_birth) ?> (<?= $age ?> سنة)</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-trophy me-2 text-primary"></i>
                            المستوى
                        </h6>
                        <p class="mb-0">
                            <span class="badge bg-info"><?= Html::encode($model->level) ?></span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-futbol me-2 text-primary"></i>
                            الرياضة
                        </h6>
                        <p class="mb-0"><?= Html::encode($model->sport) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-circle me-2 text-primary"></i>
                            الحالة
                        </h6>
                        <p class="mb-0">
                            <?php
                            $statusClass = 'bg-secondary';
                            $statusText = 'غير محدد';
                            
                            switch($model->status) {
                                case 'active':
                                    $statusClass = 'bg-success';
                                    $statusText = 'نشط';
                                    break;
                                case 'inactive':
                                    $statusClass = 'bg-danger';
                                    $statusText = 'غير نشط';
                                    break;
                                case 'suspended':
                                    $statusClass = 'bg-warning';
                                    $statusText = 'معلق';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-calendar-plus me-2 text-primary"></i>
                            تاريخ الانضمام
                        </h6>
                        <p class="mb-0"><?= Html::encode($model->created_at) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="fw-bold mb-1">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            معدل الحضور
                        </h6>
                        <p class="mb-0">
                            <span class="badge bg-success">85%</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" id="close-view-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>إغلاق
                </button>
                <a href="<?= Url::to(['update-player', 'id' => $model->id]) ?>" 
                   class="btn btn-primary me-2" 
                   data-bs-toggle="modal" 
                   data-bs-target="#editPlayerModal">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="<?= Url::to(['delete-player', 'id' => $model->id]) ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('هل أنت متأكد من حذف هذا اللاعب؟')">
                    <i class="fas fa-trash me-2"></i>حذف
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.player-avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    font-weight: bold;
    margin: 0 auto;
}

.info-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #ff6b35;
}

.btn-primary {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    border: none;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    border: none;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 600;
}

.btn-secondary {
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 600;
}
</style>

<script>
$(document).ready(function() {
    // Handle close button click
    $('#close-view-btn').on('click', function() {
        console.log('Player view modal closed');
        
        // Reset any form states if needed
        $('.modal').find('form').each(function() {
            this.reset();
        });
        
        // Clear any validation states
        $('.form-control, .form-select').removeClass('is-invalid is-valid');
        $('.invalid-feedback, .valid-feedback').remove();
        
        // Hide any error/success messages
        $('.alert').hide();
    });
    
    // Handle modal close events
    $('.modal').on('hidden.bs.modal', function() {
        console.log('Player view modal hidden');
        
        // Reset any form states
        $('.modal').find('form').each(function() {
            this.reset();
        });
        
        // Clear validation states
        $('.form-control, .form-select').removeClass('is-invalid is-valid');
        $('.invalid-feedback, .valid-feedback').remove();
        
        // Hide messages
        $('.alert').hide();
    });
    
    // Handle escape key press
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('.modal.show').length > 0) {
            console.log('Escape key pressed, closing modal');
            
            // Close the modal
            $('.modal.show').modal('hide');
            
            // Reset form states
            $('.modal').find('form').each(function() {
                this.reset();
            });
            
            // Clear validation states
            $('.form-control, .form-select').removeClass('is-invalid is-valid');
            $('.invalid-feedback, .valid-feedback').remove();
            
            // Hide messages
            $('.alert').hide();
        }
    });
});
</script>
