<?php

namespace app\controllers;
use app\models\Article;
use app\models\User;
use app\models\ImageUpload;
use app\models\Tag;
use app\models\Category;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout','create'],
                'rules' => [
                    [
                        'actions' => ['logout','create'],
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
    public function accessRules()
    {
        return [
            [
                'actions' => ['author'],
                'allow' => false,
                'roles' => ['admin'], // Only allow authenticated users
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
    
    public function actionCreate()
    {
        $model = new Article();
        $modelImage = new ImageUpload;
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');
        $selectedTags = $model->getSelectedTags(); 
        $selectedCategory = ($model->category) ? $model->category->id : '0';
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $file = UploadedFile::getInstance($model,'image');
                $tags = Yii::$app->request->post('tags');
                $category = Yii::$app->request->post('category');
                if($model->saveArticle() && $model->validate()){
                    
                    $model->saveTags($tags);
                    $model->saveCategory($category);
                    $file != null ? $model->saveImage($modelImage->uploadFile($file)) : false;
                    
                    return $this->redirect(['site/view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'selectedTags'=>$selectedTags,
            'selectedCategory'=>$selectedCategory,
            'tags'=>$tags,
            'categories'=>$categories
        ]);
    }
    public function actionDelete($id)
    {
        Article::findOne($id)->delete();

        return $this->redirect(['site/author', 'id'=>Yii::$app->user->identity->id ?? '0']);
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
                return $this->redirect(['site/view', 'id'=>$article->id]);
            }
        }
        return $this->render('image', ['model'=>$model]);
    }
    public function actionSetTags($id){
        $article = Article::findOne($id);
        $selectedTags = $article->getSelectedTags(); 
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');
        if(Yii::$app->request->isPost)
        {
            $tags = Yii::$app->request->post('tags');
            $article->saveTags($tags);
            return $this->redirect(['site/view', 'id'=>$article->id]);
        }
        return $this->render('tags', [
            'selectedTags'=>$selectedTags,
            'tags'=>$tags
        ]);
        
    }
    public function actionSetCategory($id){
        $article = $this->findModel($id);
        $selectedCategory = ($article->category) ? $article->category->id : '0';
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        if(Yii::$app->request->isPost)
        {
            $category = Yii::$app->request->post('category');
            if($article->saveCategory($category))
            {
                return $this->redirect(['view', 'id'=>$article->id]);
            }
        }

        return $this->render('category', [
            'article'=>$article,
            'selectedCategory' => $selectedCategory,
            'categories'=>$categories,
        ]);
    }
    public function actionSetAvatar($id)
    {
        $model = new ImageUpload;
        
        if($this->request->isPost)
        {
            $user = User::findOne($id);
            $file = UploadedFile::getInstance($model, 'image');
            
            if($user->saveImage($model->uploadFile($file, $user->image)))
            {
                return $this->redirect(['site/author', 'id'=>$user->id]);
            }
        }
        return $this->render('image', ['model'=>$model]);
    }
    public function actionLike($id){
        $user = Yii::$app->user->identity;
        $article = Article::findOne($id);
        $article->like($user);
        return $this->redirect(['site/view', 'id'=>$id]);
    }
    public function actionUnlike($id){
        $user = Yii::$app->user->identity;
        $article = Article::findOne($id);
        $article->unLike($user);
        return $this->redirect(['site/view', 'id'=>$id]);
    }
}
