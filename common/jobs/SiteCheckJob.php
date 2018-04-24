<?php
namespace common\jobs;

use common\components\SiteChecker;
use common\models\SiteLog;
use common\models\SiteProject;

/**
 * Class SiteCheckJob
 * @package common\jobs
 */
class SiteCheckJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $siteProjectId;

    public function execute($queue)
    {
        $site_project = SiteProject::findOne($this->siteProjectId);
        $site_checker = new SiteChecker($site_project->base_url);
        $site_project->updateAttributes(['ping_last_date' => time()]);
        $result = $site_checker->sendRequest();
        if($result['code']!=200) {
            SiteLog::create('checker',$result['message'], null, $this->siteProjectId);
        }
    }
}