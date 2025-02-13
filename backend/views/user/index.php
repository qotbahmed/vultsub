<?php

use common\grid\EnumColumn;
use common\models\User;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use yii\widgets\Pjax;


use rmrevin\yii\fontawesome\FAS;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['/helper/users-list']);

/**
 * @var yii\web\View $this
 * @var backend\models\search\UserSearch $searchModel
 * @var common\models\User $model
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('backend', 'Customers');

$this->params['breadcrumbs'][] = $this->title;

$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

?>
<style>
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
    }

    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .profile-initial {
        width: 40px;
        height: 40px;
        background: #f0f0f0;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .user-link {
        text-decoration: none;
        color: inherit;
    }

    .user-email {
        font-size: 12px;
        color: #888;
    }

</style>

<div class="row mb-3">
    <div class="col-12 col-sm-4">
        <div class="info-box custom-card statistics-card">
            <div class="info-box-content">
                <span class="info-box-text text-center text-muted"><?= Yii::t('backend', 'Total Customers') ?></span>
                <span class="info-box-number text-center text-muted mb-0"><?= User::find()->where(['user_type' => User::USER_TYPE_CUSTOMER])->andFilterWhere(['>', 'id', 3])->count() ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="info-box custom-card statistics-card">
            <div class="info-box-content">
                <span class="info-box-text text-center text-muted"><?= Yii::t('backend', 'Active Customers') ?></span>
                <span class="info-box-number text-center text-muted mb-0"><?= User::find()->where(['user_type' => User::USER_TYPE_CUSTOMER, 'status' => User::STATUS_ACTIVE])->andFilterWhere(['>', 'id', 3])->count() ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="info-box custom-card statistics-card">
            <div class="info-box-content">
                <span class="info-box-text text-center text-muted"><?= Yii::t('backend', 'Not Active Customers') ?></span>
                <span class="info-box-number text-center text-muted mb-0"><?= User::find()->where(['user_type' => User::USER_TYPE_CUSTOMER, 'status' => User::STATUS_NOT_ACTIVE])->andFilterWhere(['>', 'id', 3])->count() ?></span>
            </div>
        </div>
    </div>

</div>
<div id="CARD" class="card">
    <div class="card-header">
        <h3><?= Yii::t('backend', 'Customers List') ?></h3>
    </div>


    <div class="card-body p-0">

        <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            [
                'label' => Yii::t('backend', 'User'),
                'attribute' => 'fullName',
                'format' => 'raw',
                'value' => function ($model) {
                    $profileImage = $model->userProfile->getAvatar()
                        ? Html::img($model->userProfile->getAvatar(), ['class' => 'profile-img'])
                        : '<span class="profile-initial">' . strtoupper(mb_substr($model->userProfile['fullName'], 0, 1)) . '</span>';

                    return '<div class="user-info">
                    <div class="user-avatar">' . $profileImage . '</div>
                    <div class="user-details">
                        <div class="user-name">' . Html::a($model->userProfile->getFullName(), ['/user/view', 'id' => $model->id], ['class' => 'user-link']) . '</div>
                        <div class="user-email">' . Html::encode($model->email) . '</div>
                    </div>
                </div>';
                },
            ],
            [
                'attribute' => 'mobile',
                'enableSorting' => false,
                'value' => function ($model) {
                    return ($model->mobile) ? $model->mobile : "-";
                },
            ],

            [
                'label' => Yii::t('backend', 'Email'),
                'attribute' => 'email',
                'enableSorting' => false,
                'value' => function ($model) {
                    return !preg_match("/@testzone321/i", $model->email) ? $model->email : "-";
                },
            ],
            [
                'label' => Yii::t('backend', 'Number of points'),
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->userProfile->points_num;
                },
            ],
            [
                'class' => \common\widgets\ActionColumn::class,
                'template' => '{view} ',
                'options' => ['style' => 'width: 10px'],

            ],
        ]; ?>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'options' => [
                'class' => ['gridview', 'table-responsive'],
            ],
            'layout' => "{items}\n{pager}",

            'tableOptions' => [
                'class' => ['table', 'text-nowrap', 'mb-0'],
            ],

            // 'pjax' => true,
            // 'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-product']],
            'panel' => [
                'type' => GridView::TYPE_LIGHT,
                'heading' => false,
                'options' => ['class' => false],

            ],

            // set a label for default menu
            'export' => [
                'label' => Yii::t('backend', 'Page'),
                'fontAwesome' => true,
            ],
            // your toolbar can include the additional full export menu
            'toolbar' => [
                '{export}',
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'target' => ExportMenu::TARGET_BLANK,
                    'filename' => 'List of Customers-' . date('d-m-y'),

                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => Yii::t('backend', 'Full'),
                        'class' => 'btn btn-default',
                        'itemsBefore' => [
                            '<li class="dropdown-header">' . Yii::t('backend', 'Export All Data') . '</li>',
                        ],
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,
                    ]
                ]),
            ],
            'exportConfig' => [
                GridView::CSV => [
                    'filename' => 'List of Customers-' . date('d-m-y')
                ],
                GridView::EXCEL => [
                    'filename' => 'List of Customers-' . date('d-m-y'),
                ],

            ],
        ]); ?>


        <div class="col-md-12 text-center" style="display: flex; justify-content: center;">
            <?php echo \yii\widgets\LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'pagination']
            ]) ?>
        </div>
    </div>

    <div class="card-footer">
        <?php echo getDataProviderSummary($dataProvider) ?>
    </div>
</div>



