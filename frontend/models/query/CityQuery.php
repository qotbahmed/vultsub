<?php

namespace backend\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\backend\models\query\City]].
 *
 * @see \backend\models\query\City
 */
class CityQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \backend\models\query\City[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\models\query\City|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
