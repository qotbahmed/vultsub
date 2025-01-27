    <?php

use backend\modules\rbac\models\RbacAuthItem;
use common\models\School;
use common\grid\EnumColumn;
use common\models\User;
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
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            //'id',
            ['class' => 'yii\grid\SerialColumn'],

            //'username',
            [
                'attribute' => 'full_name',
                // 'format'    => 'raw',
                'value'     => function ($model) {
                    return $model->userProfile['fullName'];
                    // return Html::a( $model->userProfile['fullName'], ['view?id='.$model->id]) ;
                },
            ],
           'email:email',
            [
                'class' => EnumColumn::class,
                'attribute' => 'status',
                'enum' => User::statusList(),
                'filter' =>  Html::activeDropDownList($searchModel, 'status', User::statuses(),
                    ['class' => 'form-control',
                        'prompt' => Yii::t('common', 'Select')]),
            ],
            [
                'label' => "الصلاحية",
                'attribute' => "user_role",
                'format'    => 'raw',
                'value' => function($model){
                    return Html::a($model->userRole->description, ['/user-custom-role/update?id='.$model->userRole->name],
                         ['target'=>'blank']);
                },
                'filter' =>  Html::activeDropDownList($searchModel, 'user_role', User::ListCustomRoles(),
                    ['class' => 'form-control',
                        'prompt' => Yii::t('common', 'Select')]),
            ],
            [
                'attribute' => 'logged_at',
                'format' => 'datetime',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
            ],
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',

            ],
        ],
    ]); ?>

</div>
