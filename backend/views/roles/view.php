<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rbac\models\RbacAuthItem */

$this->title = 'Permissions for Role '.  $roleName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'الصلاحيات'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-auth-item-index">

<p>
    <?php echo Html::a(Yii::t('frontend', 'Create permission'), ['create','parentName'=>$roleName], ['class' => 'btn btn-success']) ?>
</p>
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        // 'name',
        [
            'label' => 'الوصف',
            'attribute' => 'description',
        ],
        // 'rule_name',
        // 'data',
        // 'created_at',
        // 'updated_at',

        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{update} {delete}',
        ],
    ],
]); ?>
</div>
