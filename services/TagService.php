<?php
namespace app\services;

use app\models\Article;
use app\models\Tag;
use yii\helpers\ArrayHelper;

class TagService
{
    public static function getList(): array
    {
        return ArrayHelper::map(Tag::find()->select(['id', 'title'])->all(), 'id', 'title');
    }

    public function saveTags(Article $article, array $tagIds): void
    {
        $article->unlinkAll('tags', true);
        foreach ($tagIds as $tagId) {
            if ($tag = Tag::findOne($tagId)) {
                $article->link('tags', $tag);
            }
        }
    }
}