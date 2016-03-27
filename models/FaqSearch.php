<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FaqSearch represents the model behind the search form about `app\models\Faq`.
 */
class FaqSearch extends Faq {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'userId', 'categoryId'], 'integer'],
            [['question', 'answer', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Faq::find()->select('id,question,answer,categoryId');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if (isset($params['categoryId']) && !empty($params['categoryId']))
            $this->categoryId = $params['categoryId'];

        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $query->orFilterWhere(['like', 'question', $_GET['q']])
                    ->orFilterWhere(['like', 'answer', $_GET['q']]);
        }

        $query->andFilterWhere([
            'categoryId' => $this->categoryId,
            'published' => 1,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
                ->andFilterWhere(['like', 'answer', $this->answer]);

        return $dataProvider;
    }

}
