<?php

namespace backend\controllers\api;

/**
 * This is the class for REST controller "SiteProjectController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SiteProjectController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\SiteProject';
}
