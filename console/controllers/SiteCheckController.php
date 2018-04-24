<?php

namespace console\controllers;

use common\jobs\SiteCheckJob;
use common\models\SiteProject;
use yii\console\Controller;

class SiteCheckController extends Controller
{

    public function actionIndex()
    {
        $site_projects = SiteProject::find()->where([
            '>=',
            'ping',
            \Yii::$app->params['pingMinDelay']
        ])->active()->all();
        echo count($site_projects) . "\n";
        foreach ($site_projects as $site_project) {
            if (time() > ($site_project->ping_last_date + $site_project->ping)) {
                \Yii::$app->queue->push(new SiteCheckJob(['siteProjectId' => $site_project->id]));
                echo "Add to queue check project with id=" . $site_project->id . "\n";
            }
        }
    }
}