<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\SiteSnapshotSearch $searchModel
 */

$this->title = Yii::t('app-model', 'Site Snapshots');
$this->params['breadcrumbs'][] = $this->title;

if (isset($actionColumnTemplates)) {
    $actionColumnTemplate = implode(' ', $actionColumnTemplates);
    $actionColumnTemplateString = $actionColumnTemplate;
} else {
    Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app-model',
            'New'), ['create'], ['class' => 'btn btn-success']);
    $actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">' . $actionColumnTemplateString . '</div>';
?>
<div class="giiant-crud site-snapshot-index">

    <?php
    //             echo $this->render('_search', ['model' =>$searchModel]);
    ?>


    <?php \yii\widgets\Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
        'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']
    ]) ?>

    <h1>
        <?= Yii::t('app-model', 'Site Snapshots') ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app-model', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">

            <?=
            \yii\bootstrap\ButtonDropdown::widget(
                [
                    'id' => 'giiant-relations',
                    'encodeLabel' => false,
                    'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('app-model',
                            'Relations'),
                    'dropdown' => [
                        'options' => [
                            'class' => 'dropdown-menu-right'
                        ],
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'url' => ['site-log/index'],
                                'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('app-model',
                                        'Site Log'),
                            ],
                            [
                                'url' => ['site-page/index'],
                                'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('app-model',
                                        'Site Page'),
                            ],
                            [
                                'url' => ['site-project/index'],
                                'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('app-model',
                                        'Site Project'),
                            ],
                            [
                                'url' => ['user/index'],
                                'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('app-model',
                                        'User'),
                            ],

                        ]
                    ],
                    'options' => [
                        'class' => 'btn-default'
                    ]
                ]
            );
            ?>
        </div>
    </div>

    <hr/>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => Yii::t('app-model', 'First'),
                'lastPageLabel' => Yii::t('app-model', 'Last'),
            ],
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => $actionColumnTemplateString,
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $options = [
                                'title' => Yii::t('cruds', 'View'),
                                'aria-label' => Yii::t('cruds', 'View'),
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-file"></span>', $url, $options);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
                // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
                [
                    'class' => yii\grid\DataColumn::className(),
                    'attribute' => 'site_project_id',
                    'value' => function ($model) {
                        if ($rel = $model->siteProject) {
                            return Html::a($rel->name, ['site-project/view', 'id' => $rel->id,], ['data-pjax' => 0]);
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                ],
                'count_pages',
                'error404',
                'error500',
                'redirect300',
                'duplicates',
                //'robots_txt:ntext',
                /*'created_at',*/
                /*'sitemap_xml',*/
                /*// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
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
                ],*/
            ],
        ]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>


