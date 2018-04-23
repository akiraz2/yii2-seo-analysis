<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "site_snapshot".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $start_url
 * @property string $created_at
 *
 * @property SitePage[] $sitePages
 * @property User $user
 */
class SiteSnapshot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_snapshot';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'user_id',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->getId();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'start_url'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['start_url'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'start_url' => Yii::t('app-model', 'Start Url'),
            'created_at' => Yii::t('app-model', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSitePages()
    {
        return $this->hasMany(SitePage::className(), ['site_snapshot_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return SiteSnapshotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SiteSnapshotQuery(get_called_class());
    }
}
