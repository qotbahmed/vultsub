<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'player-form',
    'options' => ['class' => 'form-horizontal'],
    'enableAjaxValidation' => true,
    'validationUrl' => $model->isNewRecord ? ['create-player'] : ['update-player', 'id' => $model->id],
    'enableClientValidation' => true,
    'action' => $model->isNewRecord ? ['create-player'] : ['update-player', 'id' => $model->id],
]);
?>

<!-- Error Display Area -->
<div id="form-errors" class="alert alert-danger" style="display: none;">
    <h6><i class="fas fa-exclamation-triangle me-2"></i>خطأ في البيانات:</h6>
    <ul id="error-list"></ul>
</div>

<!-- Success Message Area -->
<div id="form-success" class="alert alert-success" style="display: none;">
    <i class="fas fa-check-circle me-2"></i>
    <span id="success-message"></span>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput([
            'class' => 'form-control',
            'placeholder' => 'أدخل اسم اللاعب'
        ])->label('اسم اللاعب', ['class' => 'form-label fw-bold']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'email')->textInput([
            'type' => 'email',
            'class' => 'form-control',
            'placeholder' => 'أدخل البريد الإلكتروني'
        ])->label('البريد الإلكتروني', ['class' => 'form-label fw-bold']) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'phone')->textInput([
            'type' => 'tel',
            'class' => 'form-control',
            'placeholder' => 'أدخل رقم الهاتف'
        ])->label('رقم الهاتف', ['class' => 'form-label fw-bold']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'date_of_birth')->textInput([
            'type' => 'date',
            'class' => 'form-control'
        ])->label('تاريخ الميلاد', ['class' => 'form-label fw-bold']) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'sport')->dropDownList([
            'كرة القدم' => 'كرة القدم',
            'كرة السلة' => 'كرة السلة',
            'التنس' => 'التنس',
            'السباحة' => 'السباحة',
            'ألعاب القوى' => 'ألعاب القوى',
            'الكرة الطائرة' => 'الكرة الطائرة',
            'الجمباز' => 'الجمباز',
        ], [
            'class' => 'form-select',
            'prompt' => 'اختر الرياضة'
        ])->label('الرياضة', ['class' => 'form-label fw-bold']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'level')->dropDownList([
            'مبتدئ' => 'مبتدئ',
            'متوسط' => 'متوسط',
            'متقدم' => 'متقدم',
            'محترف' => 'محترف',
        ], [
            'class' => 'form-select'
        ])->label('المستوى', ['class' => 'form-label fw-bold']) ?>
    </div>
</div>

<?php if (!$model->isNewRecord): ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList([
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'معلق',
        ], [
            'class' => 'form-select'
        ])->label('الحالة', ['class' => 'form-label fw-bold']) ?>
    </div>
</div>
<?php endif; ?>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
    <?= Html::submitButton($model->isNewRecord ? 'إضافة اللاعب' : 'حفظ التغييرات', [
        'class' => 'btn btn-primary',
        'name' => 'submit-button',
        'id' => 'submit-btn'
    ]) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
$(document).ready(function() {
    // Handle form submission
    $('#player-form').on('beforeSubmit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = $('#submit-btn');
        var originalText = submitBtn.text();
        
        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...');
        
        // Hide previous messages
        $('#form-errors').hide();
        $('#form-success').hide();
        
        // Get the correct action URL
        var actionUrl = form.attr('action');
        if (!actionUrl) {
            // Fallback to current URL if no action is set
            actionUrl = window.location.href;
        }
        
        // Debug logging
        console.log('Form action URL:', actionUrl);
        console.log('Form data:', form.serialize());
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize() + '&ajax=player-form',
            dataType: 'json',
            success: function(response) {
                console.log('AJAX Response:', response);
                
                if (response && response.success) {
                    // Show success message
                    $('#success-message').text(response.message);
                    $('#form-success').show();
                    
                    // Redirect after a short delay
                    setTimeout(function() {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    // Show validation errors
                    displayErrors(response.errors || {});
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response Text:', xhr.responseText);
                
                // Try to parse error response
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.errors) {
                        displayErrors(errorResponse.errors);
                    } else {
                        displayErrors({'general': [errorResponse.message || 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.']});
                    }
                } catch (e) {
                    displayErrors({'general': ['حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.']});
                }
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
        
        return false;
    });
    
    // Function to display errors
    function displayErrors(errors) {
        var errorList = $('#error-list');
        errorList.empty();
        
        if (typeof errors === 'object' && errors !== null) {
            $.each(errors, function(field, fieldErrors) {
                if (Array.isArray(fieldErrors)) {
                    $.each(fieldErrors, function(index, error) {
                        errorList.append('<li>' + error + '</li>');
                    });
                } else {
                    errorList.append('<li>' + fieldErrors + '</li>');
                }
            });
        } else {
            errorList.append('<li>حدث خطأ غير متوقع</li>');
        }
        
        $('#form-errors').show();
        
        // Scroll to top of modal
        $('.modal-body').scrollTop(0);
    }
    
    // Clear errors when user starts typing
    $('input, select, textarea').on('input change', function() {
        $('#form-errors').hide();
        $('#form-success').hide();
    });
});
</script>

<style>
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
}

.form-label {
    color: #495057;
    margin-bottom: 8px;
}

.btn-primary {
    background: linear-gradient(45deg, #ff6b35, #f7931e);
    border: none;
    border-radius: 25px;
    padding: 10px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
}
</style>
