<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base-model class for table "site_project".
 *
 * @property integer $id
 * @property string $name
 * @property string $base_url
 * @property integer $ping
 * @property integer $ping_last_date
 * @property integer $reindex
 * @property string $params
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 *
 * @property \common\models\SiteLog[] $siteLogs
 * @property \common\models\SiteSnapshot[] $siteSnapshots
 * @property string $aliasModel
 */
abstract class SiteProject extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_project';
    }

    /**
     * @inheritdoc
     * @return \common\models\SiteProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\SiteProjectQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'base_url'], 'required'],
            [['ping', 'reindex', 'ping_last_date'], 'integer'],
            [['params'], 'string'],
            [['name', 'base_url'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app-model', 'ID'),
            'user_id' => Yii::t('app-model', 'User ID'),
            'name' => Yii::t('app-model', 'Name'),
            'base_url' => Yii::t('app-model', 'Base Url'),
            'ping' => Yii::t('app-model', 'Ping'),
            'reindex' => Yii::t('app-model', 'Reindex'),
            'params' => Yii::t('app-model', 'Params'),
            'status' => Yii::t('app-model', 'Status'),
            'created_at' => Yii::t('app-model', 'Created At'),
            'updated_at' => Yii::t('app-model', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteLogs()
    {
        return $this->hasMany(\common\models\SiteLog::className(), ['site_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteSnapshots()
    {
        return $this->hasMany(\common\models\SiteSnapshot::className(), ['site_project_id' => 'id']);
    }


}
