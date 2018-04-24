<?php

namespace backend\controllers\api;

/**
* This is the class for REST controller "SiteSnapshotController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SiteSnapshotController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\SiteSnapshot';
}
