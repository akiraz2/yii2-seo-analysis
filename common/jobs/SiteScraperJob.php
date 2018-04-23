<?php

namespace common\jobs;

use yii\base\BaseObject;

/**
 * Class SiteScraperJob
 * @package common\jobs
 */
class SiteScraperJob extends BaseObject implements \yii\queue\JobInterface
{
    public $snapshotId;

    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {

    }
}