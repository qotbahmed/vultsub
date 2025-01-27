<?php

namespace backend\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\backend\models\query\Settings]].
 *
 * @see \backend\models\query\Settings
 */
class SettingsQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \backend\models\query\Settings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\models\query\Settings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
