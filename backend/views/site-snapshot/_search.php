<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\SiteSnapshotSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="site-snapshot-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'site_project_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'count_pages') ?>

    <?= $form->field($model, 'robots_txt') ?>

    <?php // echo $form->field($model, 'sitemap_xml') ?>

    <?php // echo $form->field($model, 'error404') ?>

    <?php // echo $form->field($model, 'error500') ?>

    <?php // echo $form->field($model, 'redirect300') ?>

    <?php // echo $form->field($model, 'duplicates') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app-model', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app-model', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
