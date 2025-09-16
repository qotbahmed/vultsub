<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\models\UserProfile;


/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */

$model->roles =Yii::$app->session->get('UserRole');

?>

<style>
    .checkbox{
        height: 20px !important;
        display: inline !important;
        float: right !important;
        width: 20px !important;
    }
    .checkbox-label{
        position: relative !important;
        font-size: 12px !important;
        z-index: 0 !important;
        background: transparent !important;
        right: 0 !important;
        display: inline !important;
        top: 10px !important;
        font-weight: 700 !important;
        padding: 2px 5px !important;
    }
    .checkbox-parent{
        display: flex
    }
</style>


<div class="schools-form  innerForms">

<?php $form = ActiveForm::begin() ?>
<?//= $form->errorSummary($model) ?>

<div class="row">
<div class="col-md-5">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12"><?php echo $form->field($model, 'username') ?> </div>
                <div class="col-12"><?php echo $form->field($model, 'email')->input('email') ?></div>
            </div>
            <div class="row">
                <div class="col-12"><?php echo $form->field($model, 'password')->passwordInput() ?></div>
                <div class="col-12"><?php echo $form->field($model, 'password_confirm')->passwordInput() ?></div>
            </div>

            <div class="form-group row">
                <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
    
        </div>
    </div>
</div>

<div class="col-md-7">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <table class="kv-grid-table table table-bordered table-striped kv-table-wrap">
                    <tr>
                        <th style="width: 30%">
                            <?= Yii::t('backend', 'Section')?>
                        </th>
                        <th>
                            <?= Yii::t('backend', 'permissions')?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?= Yii::t('backend', 'Settings') ;?>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="settings_index" <?= ($model->checkPermissionsInUpdate('settings_index')) ? 'checked'  : '' ;?>>
                                    <label class="checkbox-label"><?= Yii::t('backend', 'Update') ;?></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= Yii::t('backend', 'Pages') ;?>
                        </td>
                        <td>
                            <div class="row">
                            <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="page_index" <?= ($model->checkPermissionsInUpdate('page_index')) ? 'checked'  : '' ;?>>
                                    <label class="checkbox-label"l><?= Yii::t('backend', 'List') ;?></label>
                                </div>
                                <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="page_update" <?= ($model->checkPermissionsInUpdate('page_update')) ? 'checked'  : '' ;?>>
                                    <label class="checkbox-label"l><?= Yii::t('backend', 'Update') ;?></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= Yii::t('backend', 'FAQs') ;?>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="faq_index" <?= ($model->checkPermissionsInUpdate('faq_index')) ? 'checked'  : '' ;?>>
                                    <label class="checkbox-label"><?= Yii::t('backend', 'List') ;?></label>
                                </div>
                                <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="faq_create" <?= ($model->checkPermissionsInUpdate('faq_create')) ? 'checked'  : '' ;?>>
                                    <label class="checkbox-label"><?= Yii::t('backend', 'Create') ;?></label>
                                </div>
                                <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="faq_update" <?= ($model->checkPermissionsInUpdate('faq_update')) ? 'checked' : '' ;?>>
                                    <label class="checkbox-label"><?= Yii::t('backend', 'Update') ;?></label>
                                </div>
                                <div class="col-md-3 checkbox-parent">
                                    <input class="checkbox" type="checkbox" name="ManagerForm[roles][]" value="faq_delete" <?= ($model->checkPermissionsInUpdate('faq_delete')) ? 'checked' : '' ;?>>
                                    <label class="checkbox-label"><?= Yii::t('backend', 'Delete') ;?></label>
                                </div>
                            </div>
                        </td>
                    </tr>


                </table>
            </div>
        </div>
    </div>
</div>

</div>
      
    <?php ActiveForm::end() ?>
        
</div>

