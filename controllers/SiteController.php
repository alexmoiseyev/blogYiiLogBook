<?php

namespace app\controllers;

use app\services\EvaluationService;
use app\services\ArticleRepository;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\Feedback;
use app\models\Article;
use app\models\Tag;


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

    private ArticleRepository $articleRepository;
    public function __construct(
        $id,
        $module,
        ArticleRepository $articleRepository,
        $config = []
    ) {
        $this->articleRepository = $articleRepository;
        parent::__construct($id, $module, $config);
    }
  
    public function actionContact()
    {
        $model = new Feedback();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
    $query = $this->articleRepository->getArticles();
    $paginatedData = $this->articleRepository->getPaginatedArticles($query);
    
    $sharedData = $this->_getSharedData();
    
    return $this->render('index', array_merge(
        $sharedData,
        $paginatedData
    ));
}
    public function actionView($id)
    {
        $article = $this->articleRepository->getArticleById($id);
        $tags = Tag::find()->all();
        $article->viewedCounter($id);
        
        return $this->render('view',[
            'tags'=>$tags,
            'article'=>$article,
        ]);
    }
    
    public function actionCategory($id)
    {
        $query = Article::find()->where(['category_id' => $id]);
        $paginatedData = $this->getPaginatedArticles($query);
        $selectedCategory = $query->where['category_id'];
        $sharedData = $this->_getSharedData();
        
        return $this->render('index', array_merge(
            ['selectedCategory'=>$selectedCategory],
            $sharedData,
            $paginatedData,
        ), 
        );
    }

    public function actionTag($id)
    {
        $query = Tag::getArticlesByTagQuery($id); 
        $paginatedData = $this->articleRepository->getPaginatedArticles($query);
        
        $sharedData = $this->_getSharedData();
        
        return $this->render('index', array_merge(
            $sharedData,
            $paginatedData
        ));
    }
    
        public function actionTagMultiple()
        {
            $tagIds = Yii::$app->request->get('tag_ids', []);
            
            $query = $this->articleRepository->findByTagIds($tagIds);
            
            $paginatedData = $this->articleRepository->getPaginatedArticles($query);
            $sharedData = $this->_getSharedData();
            
            return $this->render('index', array_merge(
                $sharedData,
                $paginatedData,
                [
                    'selectedTagIds' => $tagIds
                ]
            ));
        }

    }
