<?php
namespace console\controllers;

use common\components\SiteScraper;
use yii\console\Controller;

/**
 * Class ScraperController
 * @package console\controllers
 */
class ScraperController extends Controller {

    /**
     * @param $id
     */
    public function actionIndex($id) {
        $scraper = new SiteScraper();
        $scraper->startUrl='https://teaera.ru/';
        $scraper->snapshotId=$id;

        $scraper->start();

    }
}