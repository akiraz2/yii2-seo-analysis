<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SiteLog]].
 *
 * @see SiteLog
 */
class SiteLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SiteLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SiteLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
