<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SitePageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app-model', 'Site Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app-model', 'Create Site Page'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'site_snapshot_id',
            'url:url',
            'title',
            'meta_description',
            //'meta_keyword',
            //'tag_h1',
            //'body:ntext',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
