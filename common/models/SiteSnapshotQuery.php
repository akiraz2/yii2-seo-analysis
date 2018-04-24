<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SiteSnapshot]].
 *
 * @see SiteSnapshot
 */
class SiteSnapshotQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SiteSnapshot[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SiteSnapshot|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
