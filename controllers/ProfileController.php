<?php

namespace app\controllers;
use app\models\Article;
use app\models\User;
use app\models\Category;
use app\models\Tag;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
class ProfileController extends Controller
{
    private function _getSharedData()
    {
        $categories = Category::getAll();
        $tags = Tag::find()->all();

        return [
            'tags' => $tags,
            'categories' => $categories,
        ];
    }
    public function actionIndex($id)
    {
        $articles = Article::find()->where(['user_id' => $id])->all();
        $user = User::findOne($id);
        $sharedData = $this->_getSharedData();
        return $this->render('/site/author', array_merge(
            $sharedData,
            [
                'articles' => $articles,
                'user' => $user,
            ]
        ));
    }
    public function actionSetProfileDescription(){
        $user = User::findOne(Yii::$app->user->identity->id);
        if ($this->request->isPost) {
            if($this->request->post()){
                $about = Yii::$app->request->post('User')['about'];
                $user->saveDescription($about);
                return $this->redirect(['/profile', 'id' => $user->id]);
                
            }
        }
    }
    public function actionSubscribe($id){
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
    
        /* @var $currentUser User */
        $currentUser = User::findOne(Yii::$app->user->id);
        $user = $this->findUserById($id);
    
        $currentUser->follow($user);
        return $this->redirect(['/profile', 'id'=>$user->id]);
    }
    public function actionUnsubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }

        /* @var $currentUser User */
        $currentUser = User::findOne(Yii::$app->user->id);
        $user = $this->findUserById($id);

        $currentUser->unFollow($user);
        return $this->redirect(['/profile', 'id'=>$user->id]);
    }
    public function actionHistory($id)
    {
        $redis = Yii::$app->redis;
        $user = User::findOne($id);
        $key = "user:{$user->id}:views";

        $articles = Article::find()->where(['id' => $redis->smembers($key)])->all();

        $sharedData = $this->_getSharedData();
        return $this->render('/site/author', 
array_merge(
        $sharedData,
        [
            'articles'=>$articles,
            'user'=>$user
        ]));
    }
    private function findUserById($id){
        if($user = User::findOne($id)){
            return $user;
        }
        throw new NotFoundHttpException();
    }
}
