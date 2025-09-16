<?php

use yii\helpers\Html;
use common\models\User;
use yii\widgets\DetailView;
use backend\modules\rbac\models\RbacAuthItem;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->getPublicIdentity();
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .skin-green .content-header {
        display: none;
    }

    .content .box {
        background: transparent;
        box-shadow: none;
    }
    .tasks-list-item {
        background: #ededed;
        display: inline-flex;
        width: 47%;
        margin: 0 1% 30px;
    }
</style>

<div class="row userProfilePage">
    <div class="col-md-3">
        <div class="userprofile">
            <?php if(!empty($model->userProfile->avatar_base_url)) :?>
            <img src="<?= $model->userProfile->avatar_base_url.$model->userProfile->avatar_path ?>">
            <?php else: ?>
            <img src="/img/prof.png">
            <?php endif;?>
            <div class="body">
                <h3>
                    <?php if($model->userProfile->gender == 1 ) :?>
                    <i class="fas fa-mars"></i>
                    <?php else :?>
                    <i class="fas fa-venus"></i>
                    <?php endif;?>
                    <?= $model->userProfile->firstname. ' ' .$model->userProfile->lastname ?>
                </h3>
                <p>(<?= $model->username ?>)</p>
                <p><strong class="label label-success"><i class="far fa-user"></i> مدير النظام </strong></p>
                <?php echo DetailView::widget([
                'model' => $model->userProfile,
                'attributes' => [
                    'firstname',
                    'lastname',
                    'mobile',
                    'locale'
                ],
            ]) ?>
                <?php echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'id',
                    // 'username',
                    //'auth_key',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => function($model){
                            return User::statuses()[$model->status];
                        },
                        'format'=>'raw',
                    ],
                    // 'created_at:datetime',
                    //'updated_at:datetime',
                    'logged_at:datetime',
                ],
            ]) ?>

                <p class="">
                    <?php echo Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

                    <?php
                    if(Yii::$app->user->can('manager') or Yii::$app->user->can('administrator')){
                    ?>
                    <?php echo Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tasks-list">
        <?php foreach($categories as $category) : ?>
			<div class="tasks-list-item" id="card-<?= $category['name'] ?>">
				<div class="right w-100">
					<div class="top">
						<h4><?= $category['description'] ?></h4>
						<img src="/img/rules/<?= $category['name'] ?>.png">
					</div>
					<div class="collapse" id="<?= $category['name'] ?>">
						<ul id="cardList-<?= $category['name'] ?>">
							<?php foreach($category['modules'] as $module) : ?>
								<?php if(isset($modules['controllers'])) : ?>
									<li>
										<a href="#"><?= $module['description'] ?></a>
										<ul>
											<?php foreach($module['controllers'] as $contoller) : ?>
												<li>
													<a href="/<?= $contoller->name ?>"><?= $contoller->description ?></a>
												</li>
											<?php endforeach; ?>
										</ul>
									</li>
								<?php else : ?>
									<li>
										<a href="/<?= $module['name'] ?>"><?= $module['description'] ?></a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="actions"  data-toggle="collapse" href="#<?= $category['name'] ?>" aria-expanded="true" aria-controls="<?= $category['name'] ?>">
						<a href="" class="list w-100">عرض المزيد</a>
					</div>
				</div>
			</div>
        <?php endforeach; ?>
        </div>
        <!-- <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">مساهمات المدير</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true">صلاحيات مدير النظام</a></li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <table class="table table-striped table-bordered detail-view ">
                        <tbody>
                            <tr>
                                <td><b>إدارة المدارس</b></td>
                            </tr>
                            <tr>
                                <td> اضافة مدرسة</td>
                                <td><a href="">02</a></td>
                            </tr>
                            <tr>
                                <td> اضافة رسوم منهج جديد</td>
                                <td><a href="">02</a></td>
                            </tr>
                            <tr>
                                <td> تعديل بيانات مدرسة</td>
                                <td><a href="">02</a></td>
                            </tr>
                      
                            <tr>
                                <td><b> إدارة الإعلانات والتسويق</b></td>
                            </tr>
                            <tr>
                                <td> اضافة اعلان  - إعلانات مسؤولي المدارس</td>
                                <td><a href="">02</a></td>
                            </tr>
                            <tr>
                                <td> تعديل اعلان  - إعلانات مسؤولي المدارس</td>
                                <td><a href="">02</a></td>
                            </tr>
                            <tr>
                                <td> حذف  اعلان  - إعلانات مسؤولي المدارس</td>
                                <td><a href="">02</a></td>
                            </tr>
                            
                            <tr>
                                <td> اضافة اعلان  - إعلانات تطبيق الجوال</td>
                                <td><a href="">02</a></td>
                            </tr>
                            <tr>
                                <td> تعديل اعلان  - إعلانات تطبيق الجوال</td>
                                <td><a href="">02</a></td>
                            </tr>
                            <tr>
                                <td> حذف  اعلان  - إعلانات تطبيق الجوال</td>
                                <td><a href="">02</a></td>
                            </tr>
                        </tbody>
                    
                    </table>
                </div>
                <div class="tab-pane" id="tab_2">

                </div>

            </div>
        </div> -->
    </div>
