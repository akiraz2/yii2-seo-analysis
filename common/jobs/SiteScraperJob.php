<?php

namespace common\jobs;

use common\models\SiteSnapshot;
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
        $snapshot= SiteSnapshot::findOne($this->snapshotId);
        $snapshot->startScraping();
    }
}