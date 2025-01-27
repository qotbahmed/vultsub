<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\search\ContactUsSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('backend', 'Contact Us');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-us-index" id="CARD">
        <div class="card-header">
            <h3><?=Yii::t('backend', 'Contact Us')?></h3>
        </div>

        <div class="card-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
            <?php echo GridView::widget([
                'layout' => "{items}\n{pager}",
                'options' => [
                    'class' => ['gridview', 'table-responsive'],
                ],
                'tableOptions' => [
                    'class' => ['table', 'text-nowrap', 'mb-0'],
                ],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    'email:email',
//                     'message:ntext',
                    [
                        'attribute' => 'message',
                        'filter'=>false,
                        'value' => function ($model) {
                            return substr($model->message, 0, 90).' .. ';
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'filter'=>false,
                        'value' => function ($model) {
                            return date('Y-m-d',strtotime($model->created_at));
                        },
                    ],
                    [
                        'class' => \common\widgets\ActionColumn::class,
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>

</div>
