<?php
namespace common\helpers;

use Yii;
use common\models\Academies;

class AcademyHelper
{
    /**
     * 
     * 
     * @param Academies $model
     * @return bool
     */
    public static function checkMainAcademyStatus($model)
    {
       
        if ($model->status == 0) {
          
            if ($model->parent_id) {
                $parentAcademy = Academies::findOne($model->parent_id);
                
              
                if ($parentAcademy === null || $parentAcademy->status == 0) {
                    Yii::$app->session->removeAllFlashes();
                    Yii::$app->user->logout();
                    return false;
                }
            }

           
            Yii::$app->session->removeAllFlashes();
            Yii::$app->user->logout();
            return false;
        }

        return true; 
    }
}
