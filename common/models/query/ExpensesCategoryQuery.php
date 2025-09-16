<?php

namespace common\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\query\ExpensesCategory]].
 *
 * @see \common\models\query\ExpensesCategory
 */
class ExpensesCategoryQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \common\models\query\ExpensesCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\ExpensesCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
