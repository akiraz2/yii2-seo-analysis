<?php

namespace common\models;

use Yii;
use \common\models\base\SiteProject as BaseSiteProject;
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
            ]
        );
    }
}
