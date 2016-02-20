<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserStats;

/**
 * UserStatsSearch represents the model behind the search form about `app\models\UserStats`.
 */
class UserStatsSearch extends UserStats
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'post_views', 'profile_views'], 'integer'],
            [['total_amount', 'available_amount', 'cantake_amount'], 'number'],
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
        $query = UserStats::find();

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
            'userId' => $this->userId,
            'post_views' => $this->post_views,
            'profile_views' => $this->profile_views,
            'total_amount' => $this->total_amount,
            'available_amount' => $this->available_amount,
            'cantake_amount' => $this->cantake_amount,
        ]);

        return $dataProvider;
    }
}
