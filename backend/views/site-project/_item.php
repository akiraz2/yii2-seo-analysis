<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/** @var common\models\SiteProject @model */

?>

<div class="site-project-item">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a($model->name, ['view','id'=> $model->id]);?>
        </div>
        <div class="panel-body">
            Url: <?= HtmlPurifier::process($model->base_url) ?>
        </div>
    </div>
</div>