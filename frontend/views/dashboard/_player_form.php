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
    <button type="button" class="btn btn-secondary" id="cancel-btn" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>إلغاء
    </button>
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
    
    // Handle cancel button click
    $('#cancel-btn').on('click', function() {
        // Reset form to original state
        resetForm();
        
        // Hide any error/success messages
        $('#form-errors').hide();
        $('#form-success').hide();
        
        // Reset submit button state
        $('#submit-btn').prop('disabled', false).text($('#submit-btn').text());
        
        // Clear any validation errors
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        console.log('Form cancelled and reset');
    });
    
    // Handle modal close events
    $('.modal').on('hidden.bs.modal', function() {
        // Reset form when modal is closed
        resetForm();
        
        // Hide messages
        $('#form-errors').hide();
        $('#form-success').hide();
        
        console.log('Modal closed, form reset');
    });
    
    // Handle escape key press
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('.modal.show').length > 0) {
            // Reset form when escape is pressed
            resetForm();
            $('#form-errors').hide();
            $('#form-success').hide();
        }
    });
    
    // Function to reset form to original state
    function resetForm() {
        // Reset form fields to their original values
        var form = $('#player-form');
        form[0].reset();
        
        // Clear any custom validation states
        $('.form-control, .form-select').removeClass('is-invalid is-valid');
        $('.invalid-feedback, .valid-feedback').remove();
        
        // Reset submit button
        var submitBtn = $('#submit-btn');
        submitBtn.prop('disabled', false);
        
        // Reset button text based on form type
        if (form.find('input[name="Player[id]"]').length > 0) {
            submitBtn.text('حفظ التغييرات');
        } else {
            submitBtn.text('إضافة اللاعب');
        }
        
        // Clear any AJAX loading states
        submitBtn.find('.fa-spinner').remove();
        
        console.log('Form reset to original state');
    }
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

.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #495057);
    border: none;
    border-radius: 25px;
    padding: 10px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    color: white;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    color: white;
}

#cancel-btn {
    position: relative;
    overflow: hidden;
}

#cancel-btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

#cancel-btn:hover:before {
    left: 100%;
}
</style>
