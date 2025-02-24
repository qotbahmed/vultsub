<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[SponsorLog]].
 *
 * @see SponsorLog
 */
class SponsorLogQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SponsorLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SponsorLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
