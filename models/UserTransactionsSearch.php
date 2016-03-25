<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserTransactions;

/**
 * UserTransactionsSearch represents the model behind the search form about `app\models\UserTransactions`.
 */
class UserTransactionsSearch extends UserTransactions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userId'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
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
        $query = UserTransactions::find();

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
            'id' => $this->id,
            'userId' => $this->userId,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
