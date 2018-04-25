<?php

namespace backend\controllers\api;

/**
* This is the class for REST controller "SiteLogController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SiteLogController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\SiteLog';
}
