<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Category');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>

<div class="category-index custom-card">


    <div class="card-header">
        <h3><?=$this->title = Yii::t('backend', 'Category');?></h3>
        <p>
            <?= Html::a(Yii::t('backend', 'Create Category'), ['create'], ['class' => 'to-modal btn btn-primary text-white']) ?>

        </p>
        <div class="search-form" style="display:none">
            <?=  $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="card-border bg-gray mt-4 p-3">

        <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visibleButtons' => [

                    'delete' => function ($model) {
                        return !\common\models\Category::isDeletable($model->id);
                    },

                ],

            ],
        ];
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'class' => ['gridview', 'table-responsive'],
            ],
            'tableOptions' => [
                'class' => ['table', 'text-nowrap', 'mb-0'],
            ],
            'columns' => $gridColumn,


        ]); ?>
        <div class="col-md-12 text-center" style="display: flex; justify-content: center;">
            <?php echo \yii\widgets\LinkPager::widget([
                'pagination'=>$dataProvider->pagination,
                'options' => ['class' => 'pagination']
            ]) ?>
        </div>
    </div>
</div>
