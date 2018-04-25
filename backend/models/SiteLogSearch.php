<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SiteLog;

/**
* SiteLogSearch represents the model behind the search form about `common\models\SiteLog`.
*/
class SiteLogSearch extends SiteLog
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'site_snapshot_id', 'site_project_id'], 'integer'],
            [['category', 'message', 'created_at'], 'safe'],
];
}

/**
* @inheritdoc
*/
public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

/**
* Creates data provider instance with search query applied
*
* @param array $params
*
* @return ActiveDataProvider
*/
public function search($params)
{
$query = SiteLog::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'site_snapshot_id' => $this->site_snapshot_id,
            'site_project_id' => $this->site_project_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message]);

return $dataProvider;
}
}