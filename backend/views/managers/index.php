<?php

use backend\models\search\UserSearch;
use common\grid\EnumColumn;
use common\models\User;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;


use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Url;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['/helper/users-list']);

/**
 * @var yii\web\View $this
 * @var backend\models\search\UserSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */
if ((Yii::$app->controller->id === 'nanny' && Yii::$app->controller->action->id == 'managers')) {
    $this->title = Yii::t('backend', 'Managers');
} else {
    $this->title = Yii::t('backend', 'Managers');
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="CARD" class="card">
    <div class="card-header">
        <h3><?= Yii::t('backend', 'Managers List') ?></h3>

        <?php
            if ((Yii::$app->controller->id === 'managers' && Yii::$app->controller->action->id == 'index')) {
                echo Html::a(FAS::icon('user-plus') . ' ' . Yii::t('backend', 'Add New Manager', 
                [
                    'modelClass' => 'User',
                ]), ['create'], ['class' => 'btn btn-primary']);
            }
        ?>
    </div>

    <div class="card-body">
        <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],


            [
                'label' => Yii::t('backend', 'Username'),
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->username, ['/managers/view?id=' . $model->id], ['target' => '_self']);
                },


            ],

            [
                'label' => Yii::t('backend', 'Email'),
                'attribute' => 'email',
                'value' => function ($model) {
                    return !preg_match("/@testzone321/i", $model->email) ? $model->email : "-";
                },
            ],
//             [
//                 'attribute' => 'mobile',
//                 'enableSorting' => false,
// //                    'sorting'=>false,
// //                    'format' => 'raw',
//                 'value' => function ($model) {
//                     return $model->mobile;
//                 },
//             ],                      

            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'enableSorting' => false,
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'showMeridian' => true,
                        'todayBtn' => false,
                        'endDate' => '0d',
                    ]
                ]),
            ],


            // 'updated_at',

            [
                'class' => \common\widgets\ActionColumn::class,
                'template' => '{update} ',//{delete}
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
                    'filename' => 'List of Nannies-' . date('d-m-y'),
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
                    'filename' => 'List of Nannies-' . date('d-m-y')
                ],
                GridView::EXCEL => [
                    'filename' => 'List of Nannies-' . date('d-m-y'),
                ],

            ],
        ]); ?>
        <div class="col-md-12 text-center" style="display: flex; justify-content: center;">
            <?php echo \yii\widgets\LinkPager::widget([
                'pagination'=>$dataProvider->pagination,
                'options' => ['class' => 'pagination']
            ]) ?>
        </div>
    </div>

    <div class="card-footer">
        <?php echo getDataProviderSummary($dataProvider) ?>
    </div>
</div>



<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header" style="text-align: <?= Yii::t('backend', 'left') ?>;">
                <h5><?= Yii::t('backend', 'Confirmations') ?></h5>
            </div>
            <div class="modal-body" style="text-align: <?= Yii::t('backend', 'left') ?>;">
                <?= Yii::t('backend', 'Are you sure you want to proceed?'); ?>
                <div>
                    <br>
                    <label for="action"><?= Yii::t('backend', 'Status') ?>:</label>
                    <select id="action" name="action" class="form-control">
                        <option value="<?=User::APPROVAL_ACTIVE?>"><?= Yii::t('backend', 'Approved') ?></option>
                        <option value="<?=User::APPROVAL_NOT_ACTIVE?>"><?= Yii::t('backend', 'Not Approved') ?></option>
                    </select>
                </div>
                <br>
                <div>
                    <label for="reason"><?= Yii::t('backend', 'Reason') ?>:</label>
                    <label for="approvalReason"></label><textarea type="text" id="approvalReason" name="reason" class="form-control"> </textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmButton"><?= Yii::t('backend', 'Confirm') ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('backend', 'Cancel') ?></button>
            </div>
        </div>
    </div>
</div>

