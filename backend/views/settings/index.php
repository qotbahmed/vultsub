<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>

<div class="settings-index">


    <p>
        <?= Html::a(Yii::t('backend', 'Create Settings'), ['create'], ['class' => 'to-modal btn btn-primary']) ?>
    </p>
        <?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        'website_title',
        'phone',
        'email:email',
        'notification_email:email',
        'address',
        'facebook',
        'youtube',
        'twitter',
        'instagram',
        'linkedin',
        'whatsapp',
        'app_ios',
        'app_android',
        [
            'class' => 'kartik\grid\ActionColumn',
            "width"=>"20%",
            "template"=>'{update} {view} '//{delete}
        ],
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-settings']],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        // set a label for default menu
        'export' => [
            'label' => 'Page',
            'fontAwesome' => true,
        ],
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                ]
            ]) ,
        ],
        'exportConfig' => [
            GridView::CSV => ['label' => 'Save as CSV'],
            GridView::EXCEL => [ ],

        ],
    ]); ?>

</div>
