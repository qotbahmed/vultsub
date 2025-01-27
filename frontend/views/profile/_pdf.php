<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('backend', 'User').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'username',
        'auth_key',
        'access_token',
        'password_hash',
        'oauth_client',
        'oauth_client_user_id',
        'email:email',
        'status',
        'password_reset_token',
        'logged_at',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
    $gridColumnInvitations = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('backend', 'Inviter')
        ],
        'invitee_email:email',
        'invitee_id',
        'invitee_otp',
        'registered',
        'points_consumed',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerInvitations,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-invitations']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('backend', 'Invitations').' '. $this->title),
        ],
        'columns' => $gridColumnInvitations
    ]);
?>
    </div>
    
    <div class="row">
<?php
    $gridColumnQuestionAnswers = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        [
                'attribute' => 'question.question',
                'label' => Yii::t('backend', 'Question')
        ],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('backend', 'User')
        ],
        'answer',
        'status',
        'points_consumed',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerQuestionAnswers,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-question-answers']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('backend', 'Question Answers').' '. $this->title),
        ],
        'columns' => $gridColumnQuestionAnswers
    ]);
?>
    </div>
    
    <div class="row">
<?php
    $gridColumnQuestionRequestHint = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        [
                'attribute' => 'question.question',
                'label' => Yii::t('backend', 'Question')
        ],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('backend', 'User')
        ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerQuestionRequestHint,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-question-request-hint']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('backend', 'Question Request Hint').' '. $this->title),
        ],
        'columns' => $gridColumnQuestionRequestHint
    ]);
?>
    </div>
    
    <div class="row">
<?php
    $gridColumnTransactionsLog = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('backend', 'User')
        ],
        'amount',
        'in_out',
        'reason',
        'questions_id',
        'from_user_id',
        'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerTransactionsLog,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-transactions-log']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('backend', 'Transactions Log').' '. $this->title),
        ],
        'columns' => $gridColumnTransactionsLog
    ]);
?>
    </div>
    
    <div class="row">
<?php
    $gridColumnWithdrawal = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'hidden' => true],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('backend', 'User')
        ],
        'amount',
        [
                'attribute' => 'question.question',
                'label' => Yii::t('backend', 'Question')
        ],
        'bank_name',
        'iban',
        'routing_number',
        'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerWithdrawal,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-withdrawal']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('backend', 'Withdrawal').' '. $this->title),
        ],
        'columns' => $gridColumnWithdrawal
    ]);
?>
    </div>
</div>
