    <?php

use backend\modules\rbac\models\RbacAuthItem;
use common\models\School;
use common\grid\EnumColumn;
use common\models\User;
    use kartik\widgets\DatePicker;
    use trntv\yii\datetime\DateTimeWidget;
use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;


use yii\web\JsExpression;


$url=\yii\helpers\Url::to(['/helper/users-list']);
$schools_url= \yii\helpers\Url::to(['/helper/school-list']);


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "مديري النظام";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?php echo Html::a('إضافة مدير للنظام', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            //'id',
            ['class' => 'yii\grid\SerialColumn'],

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
                'label' => Yii::t('backend', 'User Type'),
                'value' => function ($model) {
                    return 'مدير';
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
                    ]
                ]),
            ],

//            [
//                'attribute' => 'logged_at',
//                'format' => 'datetime',
//                'filterType' => GridView::FILTER_DATE,
//                'filterWidgetOptions' => [
//                    'pluginOptions' => [
//                        'format' => 'yyyy-mm-dd',
//                        'autoclose' => true,
//                        'todayHighlight' => true,
//                    ]
//                ],
//            ],
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',

            ],
        ],
    ]); ?>

</div>
