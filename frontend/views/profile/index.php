<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $users \common\models\User */
/* @var $parent \common\models\User */
//$parent->userProfile->getFullName()
//$parent->mobile
//$parent->username
//$parent->subscriptionCount()
//$parent->childCount()
//$parent->userProfile->getNewAvatar()

$avatarPath = '/img/avatar.svg'; // Default avatar

$avatarPath = $parent->userProfile->getNewAvatar();


$this->title = Yii::t('backend', 'User');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>

<div class="container">
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="<?= $avatarPath ?? '/img/avatar.svg' ?>"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">
                        <?= $parent->userProfile->getFullName() ?>
                    </h3>

                    <p class="text-muted text-center">
                        <?= $parent->mobile ?>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item border-top-0">
                            <b><?= yii::t('common', 'Players') ?></b> <a class="float-right">
                                <?= $parent->childCount() ?>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b><?= yii::t('common', 'Subscriptions') ?></b> <a class="float-right">
                                <?= $parent->subscriptionCount() ?>
                            </a>
                        </li>
                    </ul>


                    <?= Html::a('<span class="isax isax-user-add"></span> ' . Yii::t('common', 'Add new child'), ['profile/create'],
                        ['class' => 'btn btn-primary btn-block fancybox',
                            'data-type' => 'iframe',
                            'data-size' => 'xl',]) ?>
                    <?= Html::a('<span class="isax isax-element-3"></span> ' . Yii::t('common', 'Create subscription'),
                        ['profile/subscription'],
                        ['class' => 'btn btn-primary btn-block fancybox',
                            'data-src' => Yii::$app->urlManager->createUrl(['profile/subscription']),
                            'data-type' => 'iframe',
                            'data-size' => 'xl',]) ?>
                    <!--                    --><?php //= Html::a('<span class="isax isax-user-add"></span> ' . Yii::t('common', 'Create subscription'), ['subscription'], ['class' => 'btn btn-primary btn-block', ]) ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="pb-3 border-bottom mb-3 text-primary">
                        <span class="isax isax-profile-2user"></span>
                        <?= yii::t('common', 'My childs') ?>
                    </h4>
                    <div class="active tab-pane" id="activity">

                        <?php if (empty($users)): ?>

                            <div class="alert alert-info">
                                <p>
                                    <?= Yii::t('common', 'No child found') ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($users as $player):

                            $childImg = $player->userProfile->getNewAvatar() ?? '/img/avatar.svg';
                            ?>

                            <div class="card shadow-none border mb-3">
                                <div class="card-body d-flex flex-wrap ">
                                    <div class="w-100">
                                        <div class="d-flex  w-100 border-bottom mb-3 pb-3 justify-content-between align-items-center gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="height: 50px;width: 50px;"
                                                      class="circle_icon p-0 col-auto bg-EDFFF2 bg-gradient">
                                                    <?= $childImg ? Html::img($childImg, ['alt' => '', 'class' => 'img-fluid w-100 h-100 img-circle object-cover border border-2', 'style' => 'max-width: 100%; height: auto;', 'onerror' => 'this.src="/img/avatar.svg"']) : '<img class="img-fluid w-100 h-100 img-circle object-cover border border-2" src="/img/avatar.svg"/>' ?>
                                                </span>
                                                <div class="d-flex flex-column">
                                                    <h4 class="card-title w-100 font-weight-bold gap-2">
                                                        <span>
                                                            <?= $player->userProfile->getFullName() ?>
                                                        </span>
                                                    </h4>
                                                    <small class="text-muted w-100 text-truncate">
                                                        <?= $player->mobile ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="">
                                                <ul class="list-group list-group-unbordered">
                                                    <li class="list-group-item border-0 py-0">
                                                        <b><?= yii::t('common', 'Current subscriptions') ?>:</b> <a
                                                                class="float-right">
                                                            <?= $player->currentSubscriptionCount() ?>
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item border-0 py-0">
                                                        <b><?= yii::t('common', 'Previous subscriptions') ?>:</b> <a
                                                                class="float-right">
                                                            <?= $player->previousSubscriptionCount() ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <?php
                                        $sports = $player->currentSports();
                                        if (!empty($sports)): ?>
                                            <div class="d-flex  w-100 my-3 gap-3 justify-content-start  align-items-center flex-wrap">
                                                <?php

                                                foreach ($sports as $sport):
                                                    ?>

                                                    <div class="d-inline-flex py-2 rounded px-3 bg-primary-10 text-primary align-items-center gap-3">
                                                        <img style="height: 30px;" src="<?= $sport->getUrl() ?>" alt="">
                                                        <span>
                                                        <?= $sport->title ?>
                                                    </span>
                                                    </div>
                                                <?php endforeach; ?>


                                            </div>
                                        <?php else: ?>
                                            <div class="text-center w-100 py-4">
                                                <span class="text-muted">
                                                <?= yii::t('common', 'Have no subscriptions yet') ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                    </div>


                                </div>
                            </div>


                        <?php endforeach; ?>


                        <!-- /.post -->
                    </div>
                    <!-- /.tab-content -->

                </div><!-- /.card-body -->
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="pb-3 border-bottom mb-3 text-primary">
                        <span class="isax isax-element-3"></span>
                        <?= yii::t('common', 'Pending subscriptions') ?>
                    </h4>

                    <!-- /.tab-content -->
                    <?php
                    $sports = $parent->pendingSports();
                    ?>
                    <div class="d-flex  w-100 my-3 gap-3 justify-content-start  align-items-center flex-wrap">
                        <?php
                        foreach ($sports as $sport):
                            ?>

                            <div class="d-inline-flex py-2 rounded px-3 bg-primary-10 text-primary align-items-center gap-3">
                                <img style="height: 30px;" src="<?= $sport->getUrl() ?>" alt="">
                                <span>
                                                        <?= $sport->title ?>
                                                    </span>
                            </div>
                        <?php endforeach; ?>


                    </div>

                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>


        <!-- /.col -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->