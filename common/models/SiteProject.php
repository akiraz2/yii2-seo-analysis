<?php

namespace common\models;

use common\components\StatusBehavior;
use common\models\base\SiteProject as BaseSiteProject;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_project".
 */
class SiteProject extends BaseSiteProject
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
                [
                    'class' => StatusBehavior::class
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
                [['ping', 'reindex'], 'default', 'value' => 0],
                [
                    'ping',
                    'integer',
                    'min' => Yii::$app->params['pingMinDelay'],
                    'when' => function ($model) {
                        return $model->ping > 0;
                    },
                    'whenClient' => "function (attribute, value) {
                        return value > 0;
                    }"
                ],
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastSnapshot()
    {
        return $this->hasOne(\common\models\SiteSnapshot::className(), ['site_project_id' => 'id'])->limit(1)->orderBy(['created_at' => SORT_DESC]);
    }

}
