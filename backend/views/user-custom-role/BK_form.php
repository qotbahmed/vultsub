<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\rbac\models\RbacAuthItem;
use backend\modules\rbac\models\RbacAuthItemChild;

/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */
/* @var $form yii\widgets\ActiveForm */

$rolePermissions= \yii\helpers\Url::to(['/helper/role-permissions-list']);
$otherRoles = RbacAuthItem::find()->where(['type'=>1,'assignment_category'=>RbacAuthItem::CUSTOM_ROLE_ASSIGN])
        ->andWhere(['!=','name','customRole']);
if(!$model->isNewRecord){
    $otherRoles = $otherRoles->andWhere(['!=','name',$model->name]);
}    
$otherRoles = $otherRoles->all();
?>

<div class="rbac-auth-item-form">

    <?php $form = ActiveForm::begin() ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label('اسم الصلاحية') ?>
            <?= $form->field($model, 'itemParent')->dropDownList(
                ArrayHelper::map($otherRoles, 'name', 'description'),
                ['prompt' => 'اختر الصلاحية'])->label('ترث من الصلاحية') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Slug in english') ?>
        </div>
    </div>

   

    <div class="row">

        <div class="col-md-12">
            <h3>اختر الصلاحيات المراد إضافتها</h3>
        </div>
        <div class="col-md-12">
           
            <div class="panel-group row" id="accordion" role="tablist" aria-multiselectable="true">
                <?php foreach($modules as $category) : ?>
                    <div class="col-md-6" style="margin-bottom:15px">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading<?= $category->name ?>">
                                <h4 class="panel-title">
                                    <input type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $category->name ?>" value="<?= $category->name ?>" <?= in_array($category->name, $model->subRoles) ? 'checked' : '' ?>  onclick="checkparent('<?= $category->name ?>')"/>
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $category->name ?>" aria-expanded="true" aria-controls="collapse<?= $category->name ?>">
                                        <?= $category->description ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?= $category->name ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $category->name ?>">
                                <div class="panel-body">
                                    
                                    <?php foreach($category->rbacAuthItemChildren as $module) : ?> 
                                        <table class="table table-striped table-bordered detail-view">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        <input class="<?= $category->name ?>1" type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $module->child0->name ?>" value="<?= $module->child0->name ?>" <?= in_array($module->child0->name, $model->subRoles) ? 'checked' : ''?>/>
                                                        <?= $module->child0->description ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <?php foreach($module->child0->rbacAuthItemChildren as $contoller) : ?>
                                                        <td>
                                                            <input class="<?= $category->name ?>2" type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $contoller->child0->name ?>" value="<?= $contoller->child0->name ?>" <?= in_array($contoller->child0->name, $model->subRoles) ? 'checked' : ''?>/>
                                                            <?= $contoller->child0->description ?>
                                                            <?php if($contoller->child0->rbacAuthItemChildren) : ?>
                                                                <tr>
                                                                    <?php foreach($contoller->child0->rbacAuthItemChildren as $action) : ?>
                                                                        <td>
                                                                            <input class="<?= $category->name ?>3" type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $action->child0->name ?>" value="<?= $action->child0->name ?>" <?= in_array($action->child0->name, $model->subRoles) ? 'checked' : ''?>/>
                                                                            <?= $action->child0->description ?>
                                                                        </td>
                                                                        <?php if($action->child0->rbacAuthItemChildren) : ?>
                                                                            <tr>
                                                                                <?php foreach($action->child0->rbacAuthItemChildren as $action0) : ?>
                                                                                    <td>
                                                                                        <input  class="<?= $category->name ?>4" type="checkbox" name="RbacAuthItem[subRoles][]" id="<?= $action0->child0->name ?>" value="<?= $action0->child0->name ?>" <?= in_array($action0->child0->name, $model->subRoles) ? 'checked' : ''?>/>
                                                                                        <?= $action0->child0->description ?>
                                                                                    </td>
                                                                                <?php endforeach; ?>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>
<?php
$js = <<<JS
    $(document).ready(function() {
        url = "/helper/role-permissions-list?id="+$("#rbacauthitem-itemparent").val();
            
        if($("#rbacauthitem-itemparent").val() != ""){
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $.each(data.results , function(index, val) {
                        $("#"+$.escapeSelector(val)).prop("disabled", true);
                        $("#"+$.escapeSelector(val)).prop("checked", true);
                        checkparent($.escapeSelector(val));
                        disableparent($.escapeSelector(val));
                    });
                }
            });
        }
        url = "/helper/role-permissions-list?id="+$("#rbacauthitem-name").val();
        if($("#rbacauthitem-name").val() != ""){
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $.each(data.results , function(index, val) {
                        $("#"+$.escapeSelector(val)).prop("checked", true);
                        checkparent($.escapeSelector(val));
                    });
                }
            });
        }
    });
    $("#rbacauthitem-itemparent").on("change", function() {
        url = "/helper/role-permissions-list?id="+$(this).val();
        
        if($(this).val() != ""){
            $("input[type=checkbox]").each(function() {
                if ($(this).is(":disabled")) {
                    $(this).prop("checked", false);
                    $(this).prop("disabled", false);
                }
            });
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $.each(data.results , function(index, val) {
                        $("#"+$.escapeSelector(val)).prop("disabled", true);
                        $("#"+$.escapeSelector(val)).prop("checked", true);
                        checkparent($.escapeSelector(val));
                        disableparent($.escapeSelector(val));
                    });
                }
            });
        }else{
            $("input[type=checkbox]").each(function() {
                if ($(this).is(":disabled")) {
                    $(this).prop("checked", false);
                    $(this).prop("disabled", false);
                }
            });
        }
    });

    function checkparent(parentId){
        
        if($("#"+parentId).is(":checked")){
            $("."+parentId+"1").prop("checked", true);
            $("."+parentId+"2").prop("checked", true);
            $("."+parentId+"3").prop("checked", true);
            $("."+parentId+"4").prop("checked", true);
            if($("#schools-module").is(":checked")){
                $("#schools").prop("disabled", true);
                $("#schools").prop("checked", true);
            }
        }else {
            $("."+parentId+"1").each(function() {
                if (!$(this).is(":disabled")) {
                    $(this).prop("checked", false);
                }
            });
            if (!$("."+parentId+"2").is(":disabled")) {
                $("."+parentId+"2").prop("checked", false);
            }
            if (!$("."+parentId+"3").is(":disabled")) {
                $("."+parentId+"3").prop("checked", false);
            }
            if (!$("."+parentId+"4").is(":disabled")) {
                $("."+parentId+"4").prop("checked", false);
            }
            if(!$("#schools-module").is(":checked")){
                $("#schools").prop("disabled", false);
                $("#schools").prop("checked", false);
            }
        }
    }

    function disableparent(parentId){
        if($("#"+parentId).is(":checked")){
            $("."+parentId+"1").prop("disabled", true);
            $("."+parentId+"2").prop("disabled", true);
            $("."+parentId+"3").prop("disabled", true);
            $("."+parentId+"4").prop("disabled", true);
        }
    }
    // function checkchild(childID,count){

    //     var num = count+1
    //     if($("."+childID+count).is(":checked")){
    //         console.log(childID,num)
    //         $("."+childID+num).prop("checked", true);
    //     }
    //     else if($("."+childID+count).is(":not(:checked)")){
    //         console.log(childID,num)
    //         $("."+childID+num).prop("checked", false);
    //     }
        
    // }
    
    
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>