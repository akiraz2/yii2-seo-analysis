<?php

namespace common\models;

use Yii;
use \common\models\base\SiteSnapshot as BaseSiteSnapshot;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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

    /**
     * @return string
     */
    public function getSitemapXmlUrl() {
        $filepath = '/'. \Yii::$app->params['sitemapDir'] . '/' . $this->user_id . '/';
        return $filepath.$this->sitemap_xml;
    }

    /**
     * @param $body
     * @return int
     */
    public function setSitemapXml($body) {
        $filename = md5(\Yii::$app->params['sitemapSalt'] . $this->id) . '.xml';
        $filepath = \Yii::getAlias('@backend/web/') . \Yii::$app->params['sitemapDir'] . DIRECTORY_SEPARATOR . $this->user_id . DIRECTORY_SEPARATOR;

        try {
            FileHelper::createDirectory($filepath);
            if (file_put_contents($filepath . $filename, $body)) {
                return $this->updateAttributes(['sitemap_xml' => $filename]);
            }
        } catch (Exception $exception) {
        }
        return false;
    }
}
