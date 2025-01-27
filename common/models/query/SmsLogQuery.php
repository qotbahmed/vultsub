<?php

namespace common\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\query\SmsLog]].
 *
 * @see \common\models\query\SmsLog
 */
class SmsLogQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \common\models\query\SmsLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\SmsLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
