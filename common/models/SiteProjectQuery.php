<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SiteProject]].
 *
 * @see SiteProject
 */
class SiteProjectQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    //public function get
    /**
     * @inheritdoc
     * @return SiteProject[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SiteProject|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
