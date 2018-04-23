<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SitePage */

$this->title = Yii::t('app-model', 'Update Site Page: {nameAttribute}', [
    'nameAttribute' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app-model', 'Update');
?>
<div class="site-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
