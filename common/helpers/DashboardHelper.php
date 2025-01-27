<?php
namespace common\helpers;

use common\models\QuestionRequestHint;

class DashboardHelper
{

    public static function getIsUserRequestHint($user_id,$question_id)
    {
        return QuestionRequestHint::find()->where(['user_id'=>$user_id,'question_id'=>$question_id])->exists();
    }

    public static function getQuestionGuessesCount($question_id)
    {

    }

}
