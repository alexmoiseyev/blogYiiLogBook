<?php

namespace app\controllers;
use app\models\Article;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\Feedback;

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
        $model = new Feedback();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            $model->saveFeedback(Yii::$app->user->identity);
            return $this->refresh();
        }
        return $this->render('feedback', [
            'model' => $model,
        ]);
    }
    public function actionSearch()
    {
        $search_input = Yii::$app->request->get('search');
        $search = str_replace(' ', '', $search_input);
        
        $query = Article::find()->where(['like', 'replace(title, " ", "")', $search]);
        $paginatedData = $this->_getPaginatedArticles($query);
        
        $sharedData = $this->_getSharedData();
        
        return $this->render('index', array_merge(
            $sharedData,
            $paginatedData,
            ['search' => $search]
        ));
    }
    public function actionIndex()
{
    $query = Article::find();
    $paginatedData = $this->_getPaginatedArticles($query);
    
    $sharedData = $this->_getSharedData();
    
    return $this->render('index', array_merge(
        $sharedData,
        $paginatedData
    ));
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
        $query = Article::find()->where(['category_id' => $id]);
        $paginatedData = $this->_getPaginatedArticles($query);
        
        $sharedData = $this->_getSharedData();
        
        return $this->render('index', array_merge(
            $sharedData,
            $paginatedData
        ));
    }

    public function actionTag($id)
    {
        $query = Tag::getArticlesByTagQuery($id); 
        $paginatedData = $this->_getPaginatedArticles($query);
        
        $sharedData = $this->_getSharedData();
        
        return $this->render('index', array_merge(
            $sharedData,
            $paginatedData
        ));
    }
    
    

    
}
