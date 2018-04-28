<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteSnapshot $model
 */

$this->title = Yii::t('app-model', 'Site Snapshot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Snapshots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud site-snapshot-create">

    <h1>
        <?= Yii::t('app-model', 'Site Snapshot') ?>
        <small>
            <?= $model->id ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a(
                Yii::t('app-model', 'Cancel'),
                \yii\helpers\Url::previous(),
                ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr/>

    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
