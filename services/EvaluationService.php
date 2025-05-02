<?php
namespace app\services;

use app\models\Article;
use app\models\User;

class EvaluationService
{
    public function like(Article $article, User $user): void
    {
        $article->like($user);
    }

    public function unlike(Article $article, User $user): void
    {
        $article->unLike($user);
    }
}