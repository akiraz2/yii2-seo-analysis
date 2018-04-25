<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteProject $model
 */

$this->title = Yii::t('app-model', 'Site Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app-model', 'Edit');
?>
<div class="giiant-crud site-project-update">

    <h1>
        <?= Yii::t('app-model', 'Site Project') ?>
        <small>
            <?= $model->name ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('app-model', 'View'),
            ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr/>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
