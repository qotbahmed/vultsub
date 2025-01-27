<?php
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

Pjax::begin();
$dataProvider = new ArrayDataProvider([
    'allModels' => $row,
    'pagination' => [
        'pageSize' => -1
    ]
]);
echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'formName' => 'Customer',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions'=>['hidden'=>true]],
        'title' => ['type' => TabularForm::INPUT_TEXT],
        'customer_type_id' => [
            'label' => 'Customer type',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\CustomerType::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('backend', 'Choose Customer type')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'country' => ['type' => TabularForm::INPUT_TEXT],
        'city_id' => [
            'label' => 'City',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\City::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('backend', 'Choose City')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'address' => ['type' => TabularForm::INPUT_TEXT],
        'phone' => ['type' => TabularForm::INPUT_TEXT],
        'description' => ['type' => TabularForm::INPUT_TEXTAREA],
        'lat' => ['type' => TabularForm::INPUT_TEXT],
        'lng' => ['type' => TabularForm::INPUT_TEXT],
        'avatar_path' => ['type' => TabularForm::INPUT_TEXT],
        'avatar_base_url' => ['type' => TabularForm::INPUT_TEXT],
        'status' => ['type' => TabularForm::INPUT_CHECKBOX],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  Yii::t('backend', 'Delete'), 'onClick' => 'delRowCustomer(' . $key . '); return false;', 'id' => 'customer-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Yii::t('backend', 'Customer'),
            'type' => GridView::TYPE_INFO,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('backend', 'Add Row'), ['type' => 'button', 'class' => 'btn btn-primary kv-batch-create', 'onClick' => 'addRowCustomer()']),
        ]
    ]
]);
Pjax::end();
?>
