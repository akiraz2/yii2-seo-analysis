<?php

namespace console\controllers;

use common\components\SiteChecker;
use common\jobs\SiteCheckJob;
use common\models\SiteLog;
use common\models\SiteProject;
use yii\console\Controller;
use yii\db\Expression;

/**
 * Class SiteCheckController
 * @package console\controllers
 */
class SiteCheckController extends Controller
{

    /**
     *
     */
    public function actionIndex()
    {
        $site_projects = SiteProject::find()->where([
            '>=',
            'ping',
            \Yii::$app->params['pingMinDelay']
        ])
            ->andWhere(['<', '(`ping_last_date`+`ping`)', new Expression('UNIX_TIMESTAMP()')])
            ->active()->all();

        echo count($site_projects) . "\n";
        foreach ($site_projects as $site_project) {

            $site_checker = new SiteChecker($site_project->base_url);
            $result = $site_checker->sendRequest();
            if($result['code']!=200) {
                SiteLog::create('checker',$result['message'], null, $site_project->id);
            }
            $site_project->updateAttributes(['ping_last_date' => time()]);

            //if (time() > ($site_project->ping_last_date + $site_project->ping)) {
                //\Yii::$app->queue->push(new SiteCheckJob(['siteProjectId' => $site_project->id]));
                //echo "Add to queue check project with id=" . $site_project->id . "\n";
            //}
        }
    }
}