<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Article;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    public $tag_ids;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'viewed', 'user_id', 'status', 'category_id'], 'integer'],
            [['title', 'description', 'content', 'date', 'image'], 'safe'],
            [['tag_ids'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Article::find();
        
        $this->load($params);
        
        if ($this->tag_ids) {
            $query->joinWith('tags')
                  ->andWhere(['tag.id' => $this->tag_ids])
                  ->groupBy('article.id')
                  ->having('COUNT(DISTINCT tag.id) = ' . count($this->tag_ids));
        }
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
    }
}
