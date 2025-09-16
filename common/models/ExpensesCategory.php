<?php

namespace common\models;

use Yii;
use \common\models\base\ExpensesCategory as BaseExpensesCategory;

/**
 * This is the model class for table "expenses_category".
 * 
 * @property string|null $name_en English name of the expense category
 */
class ExpensesCategory extends BaseExpensesCategory
{
    /**
     * Get the translated name based on current language after finding the record
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Use the English name if the current language is English and the name_en field is not empty
        $currentLang = Yii::$app->language;
        if ($currentLang === 'en' && !empty($this->name_en)) {
            $this->name = $this->name_en;
        }
        // For Arabic (or any other language), we keep using the default 'name' field
    }
}
