<?php
/**
 * @author Eugene Terentev <eugene@terentev.net>
 * @author Victor Gonzalez <victor@vgr.cl>
 * @var common\models\TimelineEvent $model
 */

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
?>

<?php echo FAS::icon('user-plus', ['class' => 'bg-green']) ?>
<div class="timeline-item">
    <span class="time">
        <?php       Yii::$app->formatter->locale =Yii::$app->language;
        echo FAS::icon('clock').' '.Yii::$app->formatter->asDatetime($model->created_at)
        ?>
    </span>

    <h3 class="timeline-header">
        <?php echo Yii::t('backend', 'New {user_type} {identity} has signed up', [
            'identity' => Html::a($model->data['public_identity'], [$model->data['type']==\common\models\User::USER_TYPE_CUSTOMER?'user':'nanny'.'/view', 'id' => $model->data['user_id']]),
            'created_at' => Yii::$app->formatter->asDatetime($model->data['created_at']),
            'user_type' => \common\models\User::getType($model->data['type'])
        ]) ?>
    </h3>
</div>