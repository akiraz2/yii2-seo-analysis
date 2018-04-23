<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SitePage]].
 *
 * @see SitePage
 */
class SitePageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SitePage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SitePage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
