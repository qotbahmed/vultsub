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
use common\models\Academies;
use common\models\Sport;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\helpers\Helper;
use yii\helpers\Url;

$url = \yii\helpers\Url::to(['/helper/users-list']);

$controller = Yii::$app->controller;

/**
 * @var yii\web\View $this
 * @var academyadmin\models\search\UserSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$hasFilters = !empty($searchModel->fullName) || !empty($searchModel->email) || !empty($searchModel->status) || !empty($searchModel->mobile);
$this->title = Yii::t('common', 'Players');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-element-3"></span>
            </span>
            <h4 class="mb-0">
                <?= $this->title . ' ' . User::getType(Yii::$app->session->get('userType')) ?>
            </h4>
        </div>
        <div class="mb-0 d-inline-flex align-items-center gap-2">
            <a class="btn filter_toggler <?= $hasFilters ? '' : 'collapsed' ?>" data-toggle="collapse" href="#collapseFilters" role="button" aria-expanded="false" aria-controls="collapseExample">
                <span class="isax icon isax-filter-remove"></span>
            </a>
    
        </div>
    </div>
    
    <div id="CARD" class="">
    
        <div id="collapseFilters" class="collapse <?= $hasFilters ? 'show' : '' ?>">
            <div class="section_toolbar">
                <?php
                echo $this->render('_search', [
                    'model' => $searchModel,
                    'controller' => $controller,
                ]);
                ?>
            </div>
        </div>
    
        <div class="">
    
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'hidden' => true],
                [
                    'label' => Yii::t('backend', 'Full Name'),
                    'attribute' => 'fullName',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->userProfile['fullName'], ['/user/view?id=' . $model->id], ['target' => '_self']);
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
                    'attribute' => 'mobile',
                    'enableSorting' => false,
                    'value' => function ($model) {
                        return ($model->mobile) ? $model->mobile : "-";
                    },
                ],
                [
                    'attribute' => 'status',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'filter' => Html::activeDropDownList($searchModel, 'status', User::getStatuses(''), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'Select Status')]),
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'value' => function ($model) {
                        $status = $model->usersStatuses()[$model->status];
                        $action = $model->status === User::STATUS_ACTIVE ? Yii::t('backend', 'Deactivate') : Yii::t('backend', 'Activate');
                        $confirmationMessage = Yii::t('backend', 'Are you sure you want to') . ' ' . $action . '?';
                        $confirmationTitle = Yii::t('backend', 'Confirmation');
    
                        return Html::a($status, ['toggle-status', 'id' => $model->id], [
                            'class' => 'status-toggle',
                            'data' => [
                                'confirm' => $confirmationMessage,
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'enableSorting' => false,
                    'format' => 'datetime',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'showMeridian' => true,
                            'todayBtn' => false,
                            'endDate' => '0d',
                        ],
                    ]),
                ],
            ];
    
            // Add columns based on user type
            if (Yii::$app->session->get('userType') == User::USER_TYPE_TRAINER) {
                $gridColumn[] = [
                    'attribute' => 'sport_id',
                    'label' => Yii::t('backend', 'sports'),
                    'value' => function ($model) {
                        return $model->coachProfile && $model->coachProfile->sport ? $model->coachProfile->sport->title : null;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        Sport::find()
                            ->joinWith('academySports')
                            ->where(['academy_sport.academy_id' => $controller->academyMainObj->id])
                            ->all(),
                        'id',
                        'title'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'filterInputOptions' => [
                        'placeholder' => Yii::t('backend', 'Select...'),
                        'id' => 'grid-sport-search-id',
                    ],
                ];
    
                $gridColumn[] = [
                    'label' => Yii::t('backend', 'Trainer Calendar'),
                    'value' => function ($model) {
                        // Check if there are any schedules associated with this trainer
                        $hasSchedules = \common\models\Schedules::find()->where(['trainer_id' => $model->id])->exists();
                        if ($hasSchedules) {
                            // Generate the URL to view the calendar
                            return Html::a(Yii::t('backend', 'View Calendar'), Url::to(['trainer-calendar', 'trainer_id' => $model->id]), [
                                'class' => 'btn btn-primary fancybox',
                                'data-type' => 'ajax',
                                'data-size' => 'xl',
                                'data-title' => Yii::t('backend', 'Trainer Schedule'),
                            ]);
                        } else {
                            return Yii::t('backend', 'No Schedules');
                        }
                    },
                    'format' => 'raw',
                ];
    
                $gridColumn[] = [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Attendance',
                    'template' => '{attendance}',
                    'buttons' => [
                        'attendance' => fn($url, $model) => Html::a(
                            Yii::t('backend', 'Attendance Registration'),
                            ['coach-attendance/register-attendance', 'coach_id' => $model->id],
                            [
                                'class' => 'btn btn-primary',
                                'data-confirm' => Yii::t('backend', 'Are you sure you want to register attendance for {fullName} on {day} at {time}?', [
                                    'fullName' => $model->userProfile['fullName'],
                                    'day' => Yii::$app->formatter->asDate('now', 'php:l'),
                                    'time' => Yii::$app->formatter->asTime('now', 'php:h:i A'),
                                    // 'time' => (new \DateTime('now', new \DateTimeZone('Asia/Riyadh')))->format('h:i A'),
                                    // 'time' => (new \DateTime())->format('h:i A'),
                                ]),
                                'data-method' => 'post',
                            ]
                        ),
                    ],
                ];
    
                $gridColumn[] = [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Departure',
                    'template' => '{departure}',
                    'buttons' => [
                        'departure' => fn($url, $model) => Html::a(
                            Yii::t('backend', 'Departure Registration'),
                            ['coach-attendance/register-departure', 'coach_id' => $model->id],
                            [
                                'class' => 'btn btn-danger',
                                'data-confirm' => Yii::t('backend', 'Are you sure you want to register departure for {fullName} on {day} at {time}?', [
                                    'fullName' => $model->userProfile['fullName'],
                                    'day' => Yii::$app->formatter->asDate('now', 'php:l'),
                                    'time' => Yii::$app->formatter->asTime('now', 'php:h:i A'),
                                ]),
                                'data-method' => 'post',
                            ]
                        ),
                    ],
                ];
            }
    
            $gridColumn[] = [
                'class' => \common\widgets\ActionColumn::class,
                'template' => '{update}',
                'options' => ['style' => 'width: 10px'],
            ];
            ?>
    
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'columns' => $gridColumn,
                'options' => [
                    'class' => ['gridview', 'table-responsive'],
                ],
                'layout' => "{items}\n{pager}",
                'tableOptions' => [
                    'class' => ['table', 'text-nowrap', 'mb-0'],
                ],
                'panel' => [
                    'type' => GridView::TYPE_LIGHT,
                    'heading' => false,
                    'options' => ['class' => false],
                ],
                'export' => [
                    'label' => Yii::t('backend','Page'),
                    'fontAwesome' => true,
                ],
                'toolbar' => [
                    '{export}',
                    ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumn,
                        'target' => ExportMenu::TARGET_BLANK,
                        'filename' => 'List of Customers-'. date('d-m-y'),
                        'fontAwesome' => true,
                        'dropdownOptions' => [
                            'label' => Yii::t('backend','Full'),
                            'class' => 'btn btn-default',
                            'itemsBefore' => [
                                '<li class="dropdown-header">'.Yii::t('backend','Export All Data').'</li>',
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
                        'filename' => 'List of Customers-'. date('d-m-y')
                    ],
                    GridView::EXCEL => [
                        'filename' => 'List of Customers-'. date('d-m-y'),
                    ],
                ],
            ]); ?>
    
            <div class="col-md-12 text-center" style="display: flex; justify-content: center;">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'options' => ['class' => 'pagination']
                ]) ?>
            </div>
        </div>
    
        <div class="card-footer">
            <?= getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>
