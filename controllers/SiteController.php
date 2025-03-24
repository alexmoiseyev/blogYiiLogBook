<?php

namespace app\controllers;
use app\models\Category;
use app\models\Article;
use app\models\Tag;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'author','contact'],
                'rules' => [
                    [
                        'actions' => ['logout', 'author','contact'],
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
        $search_input = Yii::$app->request->get('search');
        $search=str_replace(' ', '', $search_input);
        $articles = Article::find()->where(['like', 'replace(title, " ", "")', $search])->all();
        $sharedData = $this->_getSharedData();
        return $this->render('index', array_merge(
            $sharedData,
        [
            'articles'=>$articles,
            'search'=>$search
        ]));
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
    
    

    private function findUserById($id){
        if($user = User::findOne($id)){
            return $user;
        }
        throw new NotFoundHttpException();
    }
}
