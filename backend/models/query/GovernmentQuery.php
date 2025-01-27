<?php

namespace backend\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\backend\models\query\Government]].
 *
 * @see \backend\models\query\Government
 */
class GovernmentQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \backend\models\query\Government[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\models\query\Government|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
