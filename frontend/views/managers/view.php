<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->getPublicIdentity();
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .star-rating {
        line-height: 32px;
        font-size: 1.25em;
    }

    .star-rating .fa-star {
        color: #ea950c;
    }
</style>

<section class="content">

    <div class="row">
        <div class="col-lg-3">            
            <div class="card custom-card mb-4 mb-lg-0">
                <div class="card-body">
                    <div class="avatar-container">
                        <img src="<?= $model->userProfile->avatar ?? "/img/avatar.svg"; ?>" alt="avatar" class="img-fluid" style="width: 100px; height: 100px;">
                    </div>
                    <ul class="list-group list-group-flush rounded-3">

                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <strong>
                                <i class="fas fa-id-card " style="color: #6F42C1;"></i> <?= Yii::t('backend', 'Name') ?>
                            </strong>
                            <p class="mb-0">
                                <a class="float-right">
                                    <?= $model->username ?>
                                </a>
                            </p>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <i class="fa fa-envelope fa-lg text-warning mr-3"></i>
                            <p class="mb-0">
                                <a class="float-right" href="mailto:<?= !preg_match("/@testzone321/i", $model->email) ? $model->email : ""; ?>">
                                    <?= !preg_match("/@testzone321/i", $model->email) ? $model->email : "-"; ?>
                                </a>
                            </p>
                        </li>
                        <!-- <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <i class="fa fa-phone fa-lg mr-3" style="color: #333333;"></i>
                            <span>
                            </span>
                            <p class="mb-0">
                                <a class="float-right"><?= $model->mobile; ?></a>
                            </p>
                        </li> -->

                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <strong><i class="fas fa-info-circle "
                                       style="color: #17A2B8;"></i> <?= Yii::t('backend', 'Status') ?></strong>
                            <p class="mb-0">
                                <a class="float-right">  <?=User::getStatuses('')[$model->status]; ?> </a>
                            </p>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9">

            <div class="card custom-card">
                <div class="card-body">  
                    <div class="row">
                        <table class="kv-grid-table table table-bordered table-striped kv-table-wrap">
                            <tr>
                                <th style="width: 30%">
                                    القسم
                                </th>
                                <th>
                                    الصلاحيات
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Settings') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="settings_index" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div>                                                
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Technical Support') ;?>
                                </td>
                                <td>
                                    <div class="col-md-2">
                                        <input type="checkbox" name="UserForm[permission][]" value="technicalsupport_list" >
                                        <label><?= Yii::t('backend', 'List') ;?></label>
                                    </div> 
                                </td>                                        
                            </tr>                                    
                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Pages') ;?>
                                </td>
                                <td>
                                    <div class="col-md-2">
                                        <input type="checkbox" name="UserForm[permission][]" value="page_update" >
                                        <label><?= Yii::t('backend', 'Update') ;?></label>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Withdrawals') ;?>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Invoices') ;?>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Category') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="category_create" >
                                            <label><?= Yii::t('backend', 'Create') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="category_update" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="category_delete" >
                                            <label><?= Yii::t('backend', 'Delete') ;?></label>
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
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="faq_create" >
                                            <label><?= Yii::t('backend', 'Create') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="faq_update" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="faq_delete" >
                                            <label><?= Yii::t('backend', 'Delete') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Customer request') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="customerrequest_list" >
                                            <label><?= Yii::t('backend', 'List') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="customerrequest_view" >
                                            <label><?= Yii::t('backend', 'View') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Sessions') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="requestlog_list" >
                                            <label><?= Yii::t('backend', 'List') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="requestlog_view" >
                                            <label><?= Yii::t('backend', 'View') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'User Status Logs') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="userstatuslogs_list" >
                                            <label><?= Yii::t('backend', 'List') ;?></label>
                                        </div>      
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Education Levels') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="educationlevel_create" >
                                            <label><?= Yii::t('backend', 'Create') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="educationlevel_update" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="educationlevel_delete" >
                                            <label><?= Yii::t('backend', 'Delete') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Medical Conditions') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="medicalcondition_create" >
                                            <label><?= Yii::t('backend', 'Create') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="medicalcondition_update" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="medicalcondition_delete" >
                                            <label><?= Yii::t('backend', 'Delete') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Skills') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="skill_create" >
                                            <label><?= Yii::t('backend', 'Create') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="skill_update" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="skill_delete" >
                                            <label><?= Yii::t('backend', 'Delete') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Languages') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="language_create" >
                                            <label><?= Yii::t('backend', 'Create') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="language_update" >
                                            <label><?= Yii::t('backend', 'Update') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="language_delete" >
                                            <label><?= Yii::t('backend', 'Delete') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Customers') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="user_list" >
                                            <label><?= Yii::t('backend', 'List') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="user_view" >
                                            <label><?= Yii::t('backend', 'View') ;?></label>
                                        </div> 
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= Yii::t('backend', 'Nannies') ;?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="nanny_list" >
                                            <label><?= Yii::t('backend', 'List') ;?></label>
                                        </div> 
                                        <div class="col-md-2">
                                            <input type="checkbox" name="UserForm[permission][]" value="nanny_view" >
                                            <label><?= Yii::t('backend', 'View') ;?></label>
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

</section>
