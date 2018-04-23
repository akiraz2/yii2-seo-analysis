<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SiteSnapshot */

$this->title = Yii::t('app-model', 'Create Site Snapshot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Snapshots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-snapshot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
