<?php

namespace app\controllers;
use app\models\Category;
use app\models\Article;
use app\models\Tag;
use app\models\User;
use app\models\ArticleTag;
use app\models\ArticleUser;
use app\models\ImageUpload;
use Yii;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\httpclient\Client;
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'author'],
                'rules' => [
                    [
                        'actions' => ['logout', 'author'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    private function _getSharedData()
    {
        $categories = Category::getAll();
        $tags = Tag::find()->all();

        return [
            'tags' => $tags,
            'categories' => $categories,
        ];
    }
    private function _getUser(){
        $user_id = Yii::$app->user->identity->id ?? '0';
        return $user_id;
    }
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    public function actionSearch(){
        $latestArticles = Article::find()->orderBy('date desc')->limit(4)->all(); 
        $search_input = Yii::$app->request->get('search');
        $search=str_replace(' ', '', $search_input);
        $articles = Article::find()->where(['like', 'replace(title, " ", "")', $search])->all();
        $categories = Category::find()->all();
        $tags = Tag::find()->all();
        return $this->render('index',compact(
            'articles',
            'categories',
            'tags',
            'latestArticles',
            'search'
        ));
    }
    public function actionIndex()
    {
        $articles = Article::getAll();
        $sharedData = $this->_getSharedData();
        return $this->render('index', array_merge(
            $sharedData,
        [
            'articles'=>$articles,
        ]));
    }
    public function actionView($id)
    {
        $article = Article::findOne($id);
        $categories = Article::getAll();
        $tags = Tag::find()->all();

        $article->viewedCounter($id);
        
        return $this->render('view',[
            'tags'=>$tags,
            'categories'=>$categories,
            'article'=>$article,
        ]);
    }
    
    public function actionCategory($id)
    {
        $data = Category::getArticlesByCategory($id);
        $sharedData = $this->_getSharedData();
        return $this->render('index', array_merge($sharedData, ['articles' => $data, ]));
    }

    public function actionTag($id)
    {
        $data = Tag::getArticlesByTag($id);
        $sharedData = $this->_getSharedData();

        return $this->render('index', array_merge($sharedData, ['articles' => $data]));
    }
    public function actionAuthor($id)
    {
        $articles = Article::find()->where(['user_id'=>$id])->all();
        $user = User::findOne($id);
        $sharedData = $this->_getSharedData();
        return $this->render('author', 
array_merge(
        $sharedData,
        [
            'articles'=>$articles,
            'user'=>$user
        ]));
    }
    public function actionHistory($id)
    {
        $redis = Yii::$app->redis;
        $user = User::findOne($id);
        $key = "user:{$user->id}:views";

        $articles = Article::find()->where(['id' => $redis->smembers($key)])->all();

        $sharedData = $this->_getSharedData();
        return $this->render('author', 
array_merge(
        $sharedData,
        [
            'articles'=>$articles,
            'user'=>$user
        ]));
    }
}
