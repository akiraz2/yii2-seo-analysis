<?php

namespace common\models;

use common\components\SiteScraper;
use common\components\StatusBehavior;
use Yii;
use \common\models\base\SiteSnapshot as BaseSiteSnapshot;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "site_snapshot".
 */
class SiteSnapshot extends BaseSiteSnapshot
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }

    /**
     * @return string
     */
    public function getSitemapXmlUrl() {
        $filepath = '/'. \Yii::$app->params['sitemapDir'] . '/' . $this->user_id . '/';
        return $filepath.$this->sitemap_xml;
    }

    /**
     * @param $body
     * @return int
     */
    public function setSitemapXml($body) {
        $filename = md5(\Yii::$app->params['sitemapSalt'] . $this->id) . '.xml';
        $filepath = \Yii::getAlias('@backend/web/') . \Yii::$app->params['sitemapDir'] . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR;

        try {
            FileHelper::createDirectory($filepath);
            if (file_put_contents($filepath . $filename, $body)) {
                return $this->updateAttributes(['sitemap_xml' => $filename]);
            }
        } catch (Exception $exception) {
        }
        return false;
    }

    /**
     * @return array
     */
    public function getStatusList() {
        return [
            SiteScraper::STATUS_ACTIVE => 'Active',
            SiteScraper::STATUS_ENDED => 'Ended',
            SiteScraper::STATUS_STOPPED => 'Stopped',
            SiteScraper::STATUS_PAUSED => 'Paused'
        ];
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->getStatusList()[$this->status];
    }

    /**
     * @return array
     */
    public function getCmdList() {
        return [
            SiteScraper::CMD_ACTIVE => 'Запустить',
            SiteScraper::CMD_STOP => 'Остановить',
            SiteScraper::CMD_PAUSE => 'Поставить на паузу',
        ];
    }

    /**
     * @return mixed
     */
    public function getCmd() {
        return $this->getCmdList()[$this->cmd];
    }

    /**
     * @return int
     */
    public function start() {
        return $this->updateAttributes(['cmd' => SiteScraper::CMD_ACTIVE]);
    }

    /**
     * @throws \yii\base\ExitException
     */
    public function startScraping() {
        $this->updateAttributes(['status' => SiteScraper::STATUS_ACTIVE]);
        $project = $this->getSiteProject()->one();
        $scraper = new SiteScraper();
        $scraper->startUrl = $project->base_url;
        $scraper->snapshotId = $this->id;
        $scraper->cmd = function () {
            $sn=SiteSnapshot::findOne($this->id);
            var_dump($sn->cmd);
            return $sn->cmd;
        };
        $scraper->start();
    }
}
