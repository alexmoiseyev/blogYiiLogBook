<?php

namespace app\controllers;
use app\models\Category;
use app\models\Article;
use app\models\Tag;
use app\models\User;
use app\models\ArticleTag;
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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    

    /**
     * Login action.
     *
     * @return Response|string
     */
   
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
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
    public function actionAuthor($id)
    {
        $articles = Article::find()->where(['user_id'=>$id])->all();
        $user = User::findOne($id);
        $name = $user->name??'No author';
        $categories = Category::find()->all();
        $tags = Tag::find()->all();
        return $this->render('author', 
        [
            'articles'=>$articles,
            'categories'=>$categories,
            'tags'=>$tags,
            'name'=>$name 
        ]);
    }

  
    public function actionCreate()
    {
        $model = new Article();
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->saveArticle()) {
                return $this->redirect(['set-image', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        Article::findOne($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionSearch(){
        $latestArticles = Article::find()->orderBy('date desc')->limit(4)->all(); 
        $search_input = Yii::$app->request->get('search');
        $search=str_replace(' ', '', $search_input);
        $articles = Article::find()->where(['like', 'replace(title, " ", "")', $search])->all();
        $categories = Category::find()->all();
        $tags = Tag::find()->all();
        return $this->render('index',compact('articles','categories','tags','latestArticles'));
    }
    public function actionSetImage($id)
    {
        $model = new ImageUpload;

        if($this->request->isPost)
        {
            $article = Article::findOne($id);
            $file = UploadedFile::getInstance($model, 'image');
        
            if($article->saveImage($model->uploadFile($file, $article->image)))
            {
                return $this->redirect(['view', 'id'=>$article->id]);
            }
        }
        return $this->render('image', ['model'=>$model]);
    }
    public function actionIndex()
    {
        $latestArticles = Article::find()->orderBy('date desc')->limit(4)->all(); 
        $articles = Article::find()->all();
        $categories = Category::find()->all();
        $tags = Tag::find()->all();
        return $this->render('index', 
        [
            'latestArticles'=>$latestArticles,
            'articles'=>$articles,
            'categories'=>$categories,
            'tags'=>$tags,
        ]);
    }
    public function actionView($id)
    {
        $article = Article::findOne($id);
        $categories = Article::getAll();
        $tags = Tag::find()->all();

        $article->viewedCounter();
        
        return $this->render('single',[
            'tags'=>$tags,
            'categories'=>$categories,
            'article'=>$article,
        ]);
    }
    public function actionCategory($id)
    {
        $latestArticles = Article::find()->orderBy('date desc')->limit(4)->all(); 
        $data = Category::getArticlesByCategory($id);
        $categories = Category::getAll();
        $tags = Tag::find()->all();
        return $this->render('category',[
            'latestArticles'=>$latestArticles,
            'tags'=>$tags,
            'categories'=>$categories,
            'articles'=>$data['articles']
        ]);
    }
    public function actionTag($id)
    {
        $latestArticles = Article::find()->orderBy('date desc')->limit(4)->all(); 
        $data = Tag::getArticlesByTag($id);
        $categories = Category::getAll();
        $tags = Tag::find()->all();
        
        return $this->render('tag',[
            'latestArticles'=>$latestArticles,
            'tags'=>$tags,
            'categories'=>$categories,
            'articles'=>$data['articles']
        ]);
    }
}
