<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PostStats;

/**
 * PostStatsSearch represents the model behind the search form about `app\models\PostStats`.
 */
class PostStatsSearch extends PostStats
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'views', 'fb_share', 'tw_share', 'g_share', 'comments'], 'integer'],
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
        $query = PostStats::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'postId' => $this->postId,
            'views' => $this->views,
            'fb_share' => $this->fb_share,
            'tw_share' => $this->tw_share,
            'g_share' => $this->g_share,
            'comments' => $this->comments,
        ]);

        return $dataProvider;
    }
}
