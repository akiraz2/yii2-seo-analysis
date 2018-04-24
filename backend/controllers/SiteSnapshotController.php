<?php

namespace backend\controllers;

use common\jobs\SiteScraperJob;
use Yii;
use common\models\SiteSnapshot;
use backend\models\SiteSnapshotSearch;
use backend\controllers\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SiteSnapshotController implements the CRUD actions for SiteSnapshot model.
 */
class SiteSnapshotController extends BaseAdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SiteSnapshot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SiteSnapshotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SiteSnapshot model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SiteSnapshot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiteSnapshot();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SiteSnapshot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SiteSnapshot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionStart($id)
    {
        $model = $this->findModel($id);

        $queue = Yii::$app->queue;
        $queue->push(new SiteScraperJob([
            'id' => $id
        ]));

        Yii::$app->session->setFlash('success', 'Добавлено в очередь');

        return $this->redirect(['index']);
    }

    /**
     * Finds the SiteSnapshot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SiteSnapshot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiteSnapshot::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app-model', 'The requested page does not exist.'));
    }
}
