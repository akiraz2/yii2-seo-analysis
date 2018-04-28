<?php
/** @var common\models\SiteProject @model */

use common\components\StatusBehavior;
use common\models\SiteProject;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @param $status
 * @return string
 */
function get_panel($status) {
    switch ($status) {
        case StatusBehavior::ACTIVE: return 'panel-primary';
        case StatusBehavior::INACTIVE: return 'panel-default';
        case StatusBehavior::ARCHIVE: return 'panel-warning';
    }
}
/** @var \common\models\base\SiteSnapshot $last_snapshot */
$last_snapshot= $model->getLastSnapshot()->one();
?>

<div class="site-project-item">
    <div class="panel <?= get_panel($model->status);?>">
        <div class="panel-heading">
            <i class="fa fa-cube"></i> <?= $model->name;?>
        </div>
        <div class="panel-body">
            Url: <?= HtmlPurifier::process($model->base_url) ?> <br>
            <?= (Yii::t('app-model', 'Ping'))." ".$model->ping;?> sec <br>
            <?= Yii::t('app-model', 'Site Snapshots'). ' '. $model->getSiteSnapshots()->count();?> <br>
            <?= Html::a(Yii::t('app-model', 'Last Snapshot'), ['/site-snapshot/view','id'=> $last_snapshot->id])?>:
            <?= Yii::t('app-model', 'Count Pages'). ': '.$last_snapshot->count_pages;?>
            <?= Yii::t('app-model', 'Error404'). ': '.$last_snapshot->error404;?>
            <br>
            <?= Html::a(Yii::t('app-model', 'Go to'), ['view','id'=> $model->id]);?>
        </div>
    </div>
</div>