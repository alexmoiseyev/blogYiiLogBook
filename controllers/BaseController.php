<?php

namespace app\controllers;

use app\models\Category;
use app\models\Tag;
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
}