<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_log".
 *
 * @property int $id
 * @property int $site_snapshot_id
 * @property int $site_project_id
 * @property string $category
 * @property string $message
 * @property string $created_at
 *
 * @property SiteSnapshot $siteSnapshot
 */
class SiteLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_snapshot_id', 'site_project_id'], 'integer'],
            [['message'], 'required'],
            [['message', 'category'], 'string'],
            [['created_at'], 'safe'],
            [['site_snapshot_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteSnapshot::class, 'targetAttribute' => ['site_snapshot_id' => 'id']],
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
            'site_project_id' => Yii::t('app-model', 'Site Project ID'),
            'category' => Yii::t('app-model', 'Category'),
            'message' => Yii::t('app-model', 'Message'),
            'created_at' => Yii::t('app-model', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteSnapshot()
    {
        return $this->hasOne(SiteSnapshot::class, ['id' => 'site_snapshot_id']);
    }

    /**
     * @inheritdoc
     * @return SiteLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SiteLogQuery(get_called_class());
    }

    public static function create($category, $message, $site_snapshot_id=null) {
        $model= new SiteLog();
        $model->category= $category;
        $model->message= $message;
        $model->site_snapshot_id= $site_snapshot_id;
        $model->save();
    }
 }
