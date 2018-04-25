<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteProject $model
 */

$this->title = Yii::t('app-model', 'Site Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud site-project-create">

    <h1>
        <?= Yii::t('app-model', 'Site Project') ?>
        <small>
            <?= $model->name ?>
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
