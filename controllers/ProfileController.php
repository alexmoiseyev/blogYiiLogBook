<?php

namespace app\controllers;
use app\models\Article;
use app\models\User;
use app\models\ImageUpload;
use yii\web\UploadedFile;
use Yii;
class ProfileController extends BaseController
{
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
    public function actionSetAvatar($id)
    {
        $model = new ImageUpload();
        
        if ($this->request->isPost) {
            $user = User::findOne($id);
            $file = UploadedFile::getInstance($model, 'image');

            // Проверяем, был ли загружен файл
            if ($file && $user->saveImage($model->uploadFile($file, $user->image))) {
                // Если успешная загрузка, возвращаем JSON ответ
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'message' => 'Avatar updated successfully.',
                    'url' => \Yii::$app->urlManager->createAbsoluteUrl(['profile/', 'id' => $user->id]), // URL для редиректа
                ];
            } else {
                // В случае ошибки возвращаем сообщение
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => 'Failed to update avatar.',
                ];
            }
        }

        return $this->render('/site/image', ['model' => $model]);
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
}
