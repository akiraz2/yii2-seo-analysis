<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SiteSnapshot */

$this->title = Yii::t('app-model', 'Update Site Snapshot: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Snapshots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app-model', 'Update');
?>
<div class="site-snapshot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
