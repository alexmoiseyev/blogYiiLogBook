<?php

namespace app\controllers;

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
    
}