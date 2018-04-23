<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_page".
 *
 * @property int $id
 * @property int $site_snapshot_id
 * @property string $url
 * @property string $status_code
 * @property string $title
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $tag_h1
 * @property string $body
 * @property string $created_at
 *
 * @property SiteSnapshot $siteSnapshot
 */
class SitePage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_snapshot_id', 'url'], 'required'],
            [['site_snapshot_id'], 'integer'],
            [['body'], 'string'],
            [['url', 'title', 'meta_keyword', 'tag_h1'], 'string', 'max' => 255],
            [['meta_description'], 'string', 'max' => 512],
            [['status_code'], 'integer'],
            [['site_snapshot_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteSnapshot::className(), 'targetAttribute' => ['site_snapshot_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app-model', 'ID'),
            'site_snapshot_id' => Yii::t('app-model', 'Site Snapshot ID'),
            'url' => Yii::t('app-model', 'Url'),
            'title' => Yii::t('app-model', 'Title'),
            'meta_description' => Yii::t('app-model', 'Meta Description'),
            'meta_keyword' => Yii::t('app-model', 'Meta Keyword'),
            'tag_h1' => Yii::t('app-model', 'Tag H1'),
            'body' => Yii::t('app-model', 'Body'),
            'created_at' => Yii::t('app-model', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteSnapshot()
    {
        return $this->hasOne(SiteSnapshot::className(), ['id' => 'site_snapshot_id']);
    }

    /**
     * @inheritdoc
     * @return SitePageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SitePageQuery(get_called_class());
    }
}
