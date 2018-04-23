<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SitePage;

/**
 * SitePageSearch represents the model behind the search form of `common\models\SitePage`.
 */
class SitePageSearch extends SitePage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'site_snapshot_id'], 'integer'],
            [['url', 'title', 'meta_description', 'meta_keyword', 'tag_h1', 'body', 'created_at'], 'safe'],
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
        $query = SitePage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'site_snapshot_id' => $this->site_snapshot_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword])
            ->andFilterWhere(['like', 'tag_h1', $this->tag_h1])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
