<?php

namespace app\controllers;
use yii\data\Pagination;
use app\models\Category;
use app\models\Tag;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

class BaseController extends Controller
{
    protected function _getSharedData()
    {
        $categories = Category::getAll();
        $tags = Tag::find()->all();

        return [
            'tags' => $tags,
            'categories' => $categories,
        ];
    }
    protected function findUserById($id){
        if($user = User::findOne($id)){
            return $user;
        }
        throw new NotFoundHttpException();
    }
    protected function _getPaginatedArticles($query, $pageSize = 6, $orderBy = ['id' => SORT_DESC])
    {
        $query->orderBy($orderBy); 
        
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