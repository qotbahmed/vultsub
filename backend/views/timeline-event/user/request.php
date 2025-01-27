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
   ?>    </span>

    <h3 class="timeline-header">
        <?php echo Yii::t('backend', 'New Request! added {identity} ', [
            'identity' => Html::a($model->data['public_identity'], ['customer-request/view', 'id' => $model->data['request_id']]),
            'created_at' => Yii::$app->formatter->asDatetime($model->data['created_at'])
        ]) ?>
    </h3>
</div>