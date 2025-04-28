<?php 
namespace app\services;

use app\models\Article;
use app\models\Category;
use Yii;

class ArticleService
{
    public function createArticle(Article $article, array $postData): bool
    {
        if ($article->load($postData) && $article->save()) {
            (new TagService())->saveTags($article, $postData['tags'] ?? []);
            $article->saveCategory($postData['category'] ?? null);
            return true;
        }
        return false;
    }
}