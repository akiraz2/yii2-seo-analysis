<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\SiteProject $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('app-backend', 'Site Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-backend', 'Site Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app-model', 'View');
?>
<div class="giiant-crud site-project-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('app-backend', 'Site Project') ?>
        <small>
            <?= $model->name ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(
                '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('app-model', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']) ?>

            <?= Html::a(
                '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('app-model', 'Copy'),
                ['create', 'id' => $model->id, 'SiteProject' => $copyParams],
                ['class' => 'btn btn-success']) ?>

            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app-model', 'New'),
                ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
                . Yii::t('app-model', 'Full list'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('common\models\SiteProject'); ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'base_url:url',
            'ping',
            'reindex',
            'params:ntext',
            'created_at',
            'updated_at',
            'status',
        ],
    ]); ?>


    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('app-model', 'Delete'),
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => '' . Yii::t('app-model', 'Are you sure to delete this item?') . '',
            'data-method' => 'post',
        ]); ?>
    <?php $this->endBlock(); ?>



    <?php $this->beginBlock('SiteLogs'); ?>
    <div style='position: relative'>
        <div style='position:absolute; right: 0px; top: 0px;'>
            <?= Html::a(
                '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('app-model', 'List All') . ' Site Logs',
                ['site-log/index'],
                ['class' => 'btn text-muted btn-xs']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app-model', 'New') . ' Site Log',
                ['site-log/create', 'SiteLog' => ['site_project_id' => $model->id]],
                ['class' => 'btn btn-success btn-xs']
            ); ?>
        </div>
    </div>
    <?php Pjax::begin([
        'id' => 'pjax-SiteLogs',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-SiteLogs ul.pagination a, th a'
    ]) ?>
    <?=
    '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getSiteLogs(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-sitelogs',
            ]
        ]),
        'pager' => [
            'class' => yii\widgets\LinkPager::className(),
            'firstPageLabel' => Yii::t('app-model', 'First'),
            'lastPageLabel' => Yii::t('app-model', 'Last')
        ],
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                    $params[0] = 'site-log' . '/' . $action;
                    $params['SiteLog'] = ['site_project_id' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons' => [

                ],
                'controller' => 'site-log'
            ],
            'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'site_snapshot_id',
                'value' => function ($model) {
                    if ($rel = $model->siteSnapshot) {
                        return Html::a($rel->name, ['site-snapshot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            'category',
            'message:ntext',
            'created_at',
        ]
    ])
    . '</div>'
    ?>
    <?php Pjax::end() ?>
    <?php $this->endBlock() ?>


    <?php $this->beginBlock('SiteSnapshots'); ?>
    <div style='position: relative'>
        <div style='position:absolute; right: 0px; top: 0px;'>
            <?= Html::a(
                '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('app-model', 'List All') . ' Site Snapshots',
                ['site-snapshot/index'],
                ['class' => 'btn text-muted btn-xs']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app-model', 'New') . ' Site Snapshot',
                ['site-snapshot/create', 'SiteSnapshot' => ['site_project_id' => $model->id]],
                ['class' => 'btn btn-success btn-xs']
            ); ?>
        </div>
    </div>
    <?php Pjax::begin([
        'id' => 'pjax-SiteSnapshots',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-SiteSnapshots ul.pagination a, th a'
    ]) ?>
    <?=
    '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getSiteSnapshots(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-sitesnapshots',
            ]
        ]),
        'pager' => [
            'class' => yii\widgets\LinkPager::className(),
            'firstPageLabel' => Yii::t('app-model', 'First'),
            'lastPageLabel' => Yii::t('app-model', 'Last')
        ],
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                    $params[0] = 'site-snapshot' . '/' . $action;
                    $params['SiteSnapshot'] = ['site_project_id' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons' => [

                ],
                'controller' => 'site-snapshot'
            ],
            'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'user_id',
                'value' => function ($model) {
                    if ($rel = $model->user) {
                        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            'name',
            'start_url:url',
            'count_pages',
            'robots_txt:ntext',
            'sitemap_xml',
            'error404',
            'error500',
        ]
    ])
    . '</div>'
    ?>
    <?php Pjax::end() ?>
    <?php $this->endBlock() ?>


    <?= Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># ' . $model->id . '</b>',
                    'content' => $this->blocks['common\models\SiteProject'],
                    'active' => true,
                ],
                [
                    'content' => $this->blocks['SiteLogs'],
                    'label' => '<small>Site Logs <span class="badge badge-default">' . $model->getSiteLogs()->count() . '</span></small>',
                    'active' => false,
                ],
                [
                    'content' => $this->blocks['SiteSnapshots'],
                    'label' => '<small>Site Snapshots <span class="badge badge-default">' . $model->getSiteSnapshots()->count() . '</span></small>',
                    'active' => false,
                ],
            ]
        ]
    );
    ?>
</div>
