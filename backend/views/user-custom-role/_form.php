<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\rbac\models\RbacAuthItem;

/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */
/* @var $form yii\widgets\ActiveForm */

$rolePermissions = Url::to(['/helper/role-permissions-list']);
$otherRoles = RbacAuthItem::find()
    ->where(['type' => 1, 'assignment_category' => RbacAuthItem::CUSTOM_ROLE_ASSIGN])
    ->andWhere(['!=', 'name', 'customRole']);

if (!$model->isNewRecord) {
    $otherRoles = $otherRoles->andWhere(['!=', 'name', $model->name]);
}
$otherRoles = $otherRoles->all();
?>

<div class="rbac-auth-item-form">

    <?php $form = ActiveForm::begin() ?>

    <!-- Role Information Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label('اسم الصلاحية') ?>
                    <?= $form->field($model, 'itemParent')->dropDownList(
                        ArrayHelper::map($otherRoles, 'name', 'description'),
                        ['prompt' => 'اختر الصلاحية']
                    )->label('ترث من الصلاحية') ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Slug in English') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="row">
        <div class="col-md-12">
            <h3>اختر الصلاحيات المراد إضافتها</h3>
        </div>
        <div class="col-md-12">
            <div class="accordion" id="accordionPermissions">
                <div class="row">
                    <?php foreach ($modules as $index => $category): ?>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header custom-panel-heading" id="heading<?= $category->name ?>" style="background-color: #f7f7f7;">
                                <h5 class="mb-0">
                                    <input type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $category->name ?>" value="<?= $category->name ?>" <?= in_array($category->name, $model->subRoles) ? 'checked' : '' ?> onclick="checkparent('<?= $category->name ?>')"/>
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?= $category->name ?>" aria-expanded="true" aria-controls="collapse<?= $category->name ?>">
                                        <i class="fas fa-chevron-down mr-2"></i> <?= $category->description ?>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse<?= $category->name ?>" class="collapse" aria-labelledby="heading<?= $category->name ?>" data-parent="#accordionPermissions">
                                <div class="card-body">
                                    <?php foreach ($category->rbacAuthItemChildren as $controller): ?>
                                        <div class="controller-item mb-2 p-2" style="border: 1px solid #ddd; background-color: #f0f8ff;">
                                            <h6 style="font-weight: bold; color: #007bff;">
                                                <input class="controller-checkbox <?= $category->name ?>1" type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $controller->child0->name ?>" value="<?= $controller->child0->name ?>" <?= in_array($controller->child0->name, $model->subRoles) ? 'checked' : '' ?> data-controller-id="<?= $controller->child0->name ?>"/>
                                                <i class="fas fa-folder mr-2"></i> <?= $controller->child0->description ?>
                                            </h6>
                                            <?php if ($controller->child0->rbacAuthItemChildren): ?>
                                                <ul class="action-list list-unstyled pl-4">
                                                    <?php foreach ($controller->child0->rbacAuthItemChildren as $action): ?>
                                                        <li class="mb-1">
                                                            <input class="action-checkbox <?= $controller->child0->name ?>-action" type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $action->child0->name ?>" value="<?= $action->child0->name ?>" <?= in_array($action->child0->name, $model->subRoles) ? 'checked' : '' ?> data-controller-id="<?= $controller->child0->name ?>"/>
                                                            <i class="fas fa-file-alt mr-2" style="color: #28a745;"></i> <?= $action->child0->description ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (($index + 1) % 2 == 0): ?>
                </div><div class="row">
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group mt-3">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        // Toggle actions when controller is checked/unchecked
        $('.controller-checkbox').on('change', function() {
            var controllerId = $(this).data('controller-id');
            if ($(this).is(':checked')) {
                $('.' + controllerId + '-action').prop('checked', true);
            } else {
                $('.' + controllerId + '-action').prop('checked', false);
            }
        });

        // Handle initial checkbox states (toggle arrow icon on click)
        $('.custom-panel-heading a').on('click', function() {
            const icon = $(this).find('i');
            if ($(this).attr('aria-expanded') === 'true') {
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        });
        
        // Load existing permissions if editing
        if ($("#rbacauthitem-itemparent").val() != "") {
            loadRolePermissions($("#rbacauthitem-itemparent").val());
        }
    });

    // Function to load role permissions dynamically
    function loadRolePermissions(roleId) {
        $.ajax({
            type: "GET",
            url: "$rolePermissions?id=" + roleId,
            success: function(data) {
                $.each(data.results, function(index, val) {
                    $("#" + $.escapeSelector(val)).prop("disabled", true);
                    $("#" + $.escapeSelector(val)).prop("checked", true);
                });
            }
        });
    }
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>
