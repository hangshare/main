<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post;

/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class PostSearch extends Post {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'userId'], 'integer'],
            [['cover', 'title', 'created_at'], 'safe'],
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
    public function search($params, $tag = 'all', $postType = '') {
        $pageSize = 15;
        // $order = ' post_stats.views desc, created_at asc';
        $order = ' created_at DESC';
        
        $query = Post::find()
                ->joinWith(['postStats']);


        if ($postType == 2) {
            $pageSize = 21;
            $order = 'rand(), post_stats.views desc';
            $query->orderBy($order);
            $query->groupBy('userId');
        }
        $query->orderBy($order);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => $pageSize),
        ]);

        $this->load($params);
        if ($postType == 0) {
            $query->where('post.type = 0');
        }

        if (!$this->validate()) {
            return $dataProvider;
        }


        if (isset($_GET['q'])) {
            $query->joinWith(['postTags', 'postTags.tags', 'postBodies']);
            $query->andFilterWhere([
                'or',
                ['like', 'title', $_GET['q']],
                ['like', 'tags.name', $_GET['q']],
                ['like', 'post_body.body', $_GET['q']],
            ]);
        }
        if ($tag !== 'all') {
            $query->joinWith(['postTags', 'postTags.tags']);
            $query->andFilterWhere(['like', 'tags.name', $tag]);
        }

        $query->andWhere(['<>', 'cover', '']);
        return $dataProvider;
    }

}
