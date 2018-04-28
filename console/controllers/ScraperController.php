<?php

namespace console\controllers;

use common\components\SiteScraper;
use common\models\SiteSnapshot;
use yii\console\Controller;

/**
 * Class ScraperController
 * @package console\controllers
 */
class ScraperController extends Controller
{

    /**
     * @param $id
     * @throws \yii\db\Exception
     */
    public function actionIndex($id)
    {
        \Yii::$app->db->createCommand('SET SESSION wait_timeout = 28800;')->execute();
        $snapshot= SiteSnapshot::findOne($id);
        $snapshot->startScraping();
    }
}