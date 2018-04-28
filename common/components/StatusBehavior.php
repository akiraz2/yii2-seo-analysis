<?php

namespace common\components;

use Yii;
use yii\base\Behavior;

/**
 * Class StatusBehavior
 * @package common\components
 */
class StatusBehavior extends Behavior
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const ARCHIVE = 2;

    /**
     * @return array
     */
    public static function getStatusList() {
        return [
            self::INACTIVE => Yii::t('app-model', 'Inactive'),
            self::ACTIVE => Yii::t('app-model', 'Active'),
            self::ARCHIVE => Yii::t('app-model', 'Archive')
        ];
    }

    /**
     * @return array
     */
    public function getStatusList2() {
        return self::getStatusList();
    }

    /**
     * @return mixed|string
     */
    public function getStatus() {
        if($this->owner->status===null)
            return "-";
        $items = self::getStatusList();
        return $items[$this->owner->status];
    }
}