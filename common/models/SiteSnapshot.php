<?php

namespace common\models;

use Yii;
use \common\models\base\SiteSnapshot as BaseSiteSnapshot;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_snapshot".
 */
class SiteSnapshot extends BaseSiteSnapshot
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
            ]
        );
    }
}
