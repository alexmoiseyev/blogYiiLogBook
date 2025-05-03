<?php
namespace app\services;

use app\models\Article;
use yii\db\ActiveQuery;
use yii\data\Pagination;

use Yii;

class ArticleRepository
{
    public function getArticleById(int $id): Article
    {
        $article = Article::findOne($id);
        if (!$article) {
            throw new \yii\web\NotFoundHttpException('Статья не найдена');
        }
        return $article;
    }
    public function getArticles()
    {
        $cache = Yii::$app->cache;
        $key = 'all_articles';

        $query = $cache->get($key);

        if ($query === false) {
            $query = Article::find(); // Добавьте нужные условия
            $cache->set($key, $query, 3600);
        }

        if (!$query) {
            throw new \yii\web\NotFoundHttpException('Статей нет');
        }

        return $query;
    }
    public function findByTagIds(array $tagIds): ActiveQuery{
            return Article::find()
        ->innerJoinWith('tags')
        ->where(['tag.id' => $tagIds])
        ->groupBy('article.id');
    }
    public function getPaginatedArticles($query, $pageSize = 6, $orderBy = ['id' => SORT_DESC])
    {
        
        if (!$query instanceof \yii\db\ActiveQuery) {
            throw new \InvalidArgumentException('Parameter $query must be an instance of ActiveQuery');
        }
        $countQuery = clone $query;

        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $pageSize,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
        
        $articles = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        
        return [
            'articles' => $articles,
            'pages' => $pages,
        ];
    }

}