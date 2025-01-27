<?php

namespace common\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\query\InvitationForm]].
 *
 * @see \common\models\query\InvitationForm
 */
class InvitationFormQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \common\models\query\InvitationForm[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\InvitationForm|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
