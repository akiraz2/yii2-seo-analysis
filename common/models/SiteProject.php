<?php

namespace common\models;

use common\models\base\SiteProject as BaseSiteProject;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_project".
 */
class SiteProject extends BaseSiteProject
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

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
}
