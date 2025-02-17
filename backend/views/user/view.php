<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->getPublicIdentity();
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
?>
<div class="user-view">

    <p>
        <?php echo Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary text-white mb-3']) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            //'auth_key',
            'email:email',

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->usersStatuses()[$model->status];
                },
                'format' => 'raw',
            ],
            'mobile',

            'created_at:datetime',
            //'updated_at:datetime',
            'logged_at:datetime',
        ],
    ]) ?>


    <hr/>

    <?php echo DetailView::widget([
        'model' => $model->userProfile,
        'attributes' => [

            'firstname',
            // 'lastname',

//            [
//                'attribute' => 'country_id',
//                'value' => function($model){
//                    return $model->country_id ?  $model->country->name : '' ;
//                },
//                'format'=>'raw',
//            ],
//
//            [
//                'attribute' => 'city_id',
//                'value' => function($model){
//                    return $model->city_id ?  $model->city->name : '' ;
//                },
//                'format'=>'raw',
//            ],
            // 'mobile',



        ],
    ]) ?>
</div>
