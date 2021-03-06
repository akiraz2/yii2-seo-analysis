<?php

use dmstr\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var common\models\SiteSnapshot $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('app-model', 'Site Snapshot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app-model', 'Site Snapshots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app-model', 'View');
?>
<div class="giiant-crud site-snapshot-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('app-model', 'Site Snapshot') ?>
        <small>
            <?= $model->id ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(
                '<span class="glyphicon glyphicon-"></span> ' . Yii::t('app-model', 'Start'),
                ['start', 'id' => $model->id, 'SiteSnapshot' => $copyParams],
                ['class' => 'btn btn-success']) ?>

            <?= Html::a(
                '<span class="glyphicon glyphicon-"></span> ' . Yii::t('app-model', 'Stop'),
                ['stop', 'id' => $model->id, 'SiteSnapshot' => $copyParams],
                ['class' => 'btn btn-danger']) ?>

            <?= Html::a(
                '<span class="glyphicon glyphicon-"></span> ' . Yii::t('app-model', 'Pause'),
                ['pause', 'id' => $model->id, 'SiteSnapshot' => $copyParams],
                ['class' => 'btn btn-warning']) ?>

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

    <?php $this->beginBlock('common\models\SiteSnapshot'); ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
            [
                'format' => 'html',
                'attribute' => 'site_project_id',
                'value' => ($model->siteProject ?
                    Html::a('<i class="glyphicon glyphicon-list"></i>', ['site-project/index']) . ' ' .
                    Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> ' . $model->siteProject->name,
                        ['site-project/view', 'id' => $model->siteProject->id,]) . ' ' .
                    Html::a('<i class="glyphicon glyphicon-paperclip"></i>',
                        ['create', 'SiteSnapshot' => ['site_project_id' => $model->site_project_id]])
                    :
                    '<span class="label label-warning">?</span>'),
            ],
            'Status',
            'Cmd',
            'count_pages',
            'error404',
            'error500',
            'redirect300',
            'duplicates',
            'robots_txt:ntext',
            'created_at',
            'sitemap_xml',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat

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
                ['site-log/create', 'SiteLog' => ['site_snapshot_id' => $model->id]],
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
                    $params['SiteLog'] = ['site_snapshot_id' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons' => [

                ],
                'controller' => 'site-log'
            ],
            'id',
            'site_project_id',
            'category',
            'message:ntext',
            'created_at',
        ]
    ])
    . '</div>'
    ?>
    <?php Pjax::end() ?>
    <?php $this->endBlock() ?>


    <?php $this->beginBlock('SitePages'); ?>
    <div style='position: relative'>
        <div style='position:absolute; right: 0px; top: 0px;'>
            <?= Html::a(
                '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('app-model', 'List All') . ' Site Pages',
                ['site-page/index'],
                ['class' => 'btn text-muted btn-xs']
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app-model', 'New') . ' Site Page',
                ['site-page/create', 'SitePage' => ['site_snapshot_id' => $model->id]],
                ['class' => 'btn btn-success btn-xs']
            ); ?>
        </div>
    </div>
    <?php Pjax::begin([
        'id' => 'pjax-SitePages',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-SitePages ul.pagination a, th a'
    ]) ?>
    <?=
    '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getSitePages(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-sitepages',
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
                    $params[0] = 'site-page' . '/' . $action;
                    $params['SitePage'] = ['site_snapshot_id' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons' => [

                ],
                'controller' => 'site-page'
            ],
            'id',
            'url:url',
            'canonical',
            'status_code',
            'request_time',
            'title',
            'meta_description',
            'meta_keyword',
            'tag_h1',
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
                    'content' => $this->blocks['common\models\SiteSnapshot'],
                    'active' => true,
                ],
                [
                    'content' => $this->blocks['SitePages'],
                    'label' => '<small>Site Pages <span class="badge badge-default">' . $model->getSitePages()->count() . '</span></small>',
                    'active' => false,
                ],
                [
                    'content' => $this->blocks['SiteLogs'],
                    'label' => '<small>Site Logs <span class="badge badge-default">' . $model->getSiteLogs()->count() . '</span></small>',
                    'active' => false,
                ],
            ]
        ]
    );
    ?>
</div>
