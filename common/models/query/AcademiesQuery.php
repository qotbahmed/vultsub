<?php

//namespace app\models;
namespace common\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Academies]].
 *
 * @see \app\models\Academies
 */
class AcademiesQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Academies[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Academies|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
