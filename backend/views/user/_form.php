<?php

use common\models\User;
use yii\helpers\Html;
use common\models\UserProfile;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Academies;
use kartik\password\PasswordInput;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
/* @var $profile common\models\UserProfile */

$model->roles = Yii::$app->session->get('UserRole');

// Fetch the specific academy record of the user being updated
$academy = Academies::find()->where(['id' => $profile->academy_id])->one();

// Prepare the list of main academies for the dropdown 
$academyDropdownList = ArrayHelper::map(
    Academies::find()
        ->where(['main' => 1])
        ->andWhere(['NOT IN', 'id', 
            \common\models\UserProfile::find()
                ->select('academy_id')
                ->innerJoin('user', 'user.id = user_profile.user_id')
                ->where(['user.user_type' => 3])
                ->column() // Fetching an array of academy_ids with managers
        ])
        ->orderBy('id')
        ->all(),
    'id',
    'title'
);

// Add the specific academy to the dropdown list if it exists (in update)
if ($academy !== null) {
    $academyDropdownList[$academy->id] = $academy->title;
}

// // Fetch main academies and build a map of their titles
// $mainAcademiesMap = ArrayHelper::map(
//     Academies::find()->where(['main' => 1])->all(), 
//     'id', 
//     'title'
// );
// // Fetch sub-branches and create a combined title list
// $subBranchesMap = ArrayHelper::map(
//     Academies::find()->where(['not', ['parent_id' => null]])->all(), 
//     'id', 
//     function($model) use ($mainAcademiesMap) {
//         return $mainAcademiesMap[$model->parent_id] . ' - ' . $model->title;
//     }
// );
?>
<div class="user-form">

    <div class="card">
        <div class="card-body">

            <?php $form = ActiveForm::begin([
                'id' => 'user-form',
                'options' => [
                    'class' => 'needs-validation',
                    // 'novalidate' => 'novalidate'
                    'enableClientValidation' => true,
                ]
            ]) ?>
            <?= $form->errorSummary($model) ?>

            <div class="col-md-4" style="text-align: right">
                <?= $form->field($profile, 'picture')->widget(\trntv\filekit\widget\Upload::class, [
                    'url' => ['avatar-upload']
                ]) ?>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'email')->textInput() ?>
                </div>

                <div class="col-md-8">
                    <?= $form->field($model, 'password')->widget(PasswordInput::classname()) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($profile, 'firstname')->textInput() ?>
                </div>

                <?php /*
                <div class="col-md-4">
                    <?= $form->field($profile, 'lastname')->textInput() ?>
                </div>
                */ ?>

<div class="col-md-4">
    <?= $form->field($model, 'mobile')->textInput([
        'maxlength' => true,
        'placeholder' => 'رقم الجوال', // Placeholder text in Arabic
        'pattern' => '^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$',
        'title' => 'أدخل رقم جوال سعودي يبدء 009665, 9665, +9665, 05',
        'class' => 'form-control',
        'id' => 'user-mobile'
    ]) ?>
    <div id="mobile-feedback" class="invalid-feedback"></div>
</div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'gender')->radioList([
                        UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                        UserProfile::GENDER_MALE => Yii::t('backend', 'Male')
                    ],['custom' => true, 'inline' => true]) ?>
                </div>
            </div>


            <?php if (Yii::$app->session->get('userType') == User::USER_TYPE_ACADEMY_ADMIN): ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($profile, 'academy_id')->dropDownList(
                        $academyDropdownList,
                        ['prompt' => Yii::t('backend', 'Select Academy')]
                    ) ?>
                </div>
            </div>

            <?php else: ?>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($profile, 'academy_id')->widget(Select2::classname(), [
                        'data' => $mainAcademiesMap,
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Select Main Academy'),
                            'id' => 'main-academy-id',
                            'value' => $selectedMainAcademyId, // Pre-select the main academy
                            'onchange' => 'loadBranches($(this).val())',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'academy_id')->widget(Select2::classname(), [
                        'data' => $branchesMap, // This will be populated via AJAX
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Select Branch'),
                            'id' => 'branch-id',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
            </div>
            
            <?php endif; ?>

        </div>

        <div class="pt-3 border-top mt-3 text-right">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>

</div>



<script>
function loadBranches(mainAcademyId) {
    $.ajax({
        url: '<?= Yii::$app->urlManager->createUrl(['user/branches']) ?>',
        type: 'POST',
        data: {
            id: mainAcademyId,
            '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
        },
        dataType: 'json',
        success: function(data) {
            var branchDropdown = $('#branch-id');
            branchDropdown.empty(); // Clear existing options

            if (data.length > 0) {
                $.each(data, function(index, item) {
                    branchDropdown.append($('<option>', {
                        value: item.value,
                        text: item.label
                    }));
                });

                // Set the selected branch if applicable
                var selectedBranchId = '<?= $selectedBranchId ?>';
                if (selectedBranchId) {
                    branchDropdown.val(selectedBranchId).trigger('change'); // Trigger change to update Select2
                }
            } else {
                branchDropdown.append($('<option>', {
                    value: '',
                    text: 'No branches available'
                }));
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + '\n' + error);
        }
    });
}
</script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('user-form');
    var mobileInput = form.querySelector('#user-mobile');
    var feedback = document.getElementById('mobile-feedback');

    mobileInput.addEventListener('input', function() {
        var validity = mobileInput.validity;
        if (validity.valid) {
            feedback.textContent = '';
            feedback.className = 'invalid-feedback';
        } else {
            feedback.textContent = mobileInput.title;
            feedback.className = 'invalid-feedback d-block';
        }
    });

    // Handle form submission
    form.addEventListener('submit', function(event) {
        if (!mobileInput.validity.valid) {
            event.preventDefault(); // Prevent form submission if invalid
            feedback.textContent = mobileInput.title;
            feedback.className = 'invalid-feedback d-block';
        }
    });
});
</script>



<style>
  .invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875em;
}

.invalid-feedback.d-block {
    display: block;
}

</style>
