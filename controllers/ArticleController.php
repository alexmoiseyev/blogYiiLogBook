<?php

namespace app\controllers;

use app\services\ArticleService;
use app\services\TagService;
use app\services\ImageUploadService;
use app\services\EvaluationService;

use app\models\Article;
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
                'only' => ['logout','create','like','unlike'],
                'rules' => [
                    [
                        'actions' => ['logout','create','like','unlike'],
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
    private $articleService;
    private $tagService;
    private $evaluationService;
    public function __construct(
        $id, 
        $module, 
        ArticleService $articleService, 
        TagService $tagService, 
        EvaluationService $evaluationService,
        $config = [])
    {
        $this->articleService = $articleService;
        $this->tagService = $tagService;
        $this->evaluationService = $evaluationService;
        parent::__construct($id, $module, $config);
    }
    public function actionCreate()
    {
        $model = new Article();
        $selectedTags = $model->getSelectedTags(); 
        $selectedCategory = ($model->category) ? $model->category->id : '0';
        $tagService = $this->tagService;
        $imageService = new ImageUploadService();

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($this->articleService->createArticle($model, $this->request->post())) {
                    $file = UploadedFile::getInstance($model, 'image');
                    if ($file) {    
                        $imageName = $imageService->upload($file);
                        if ($imageName === false) {
                            throw new \Exception('Ошибка загрузки изображения. Возможные ошибки: минимальное разрешение 250x250px');
                        }
                        $model->image = $imageName;
                        $model->save(false); 
                    }
                    $transaction->commit();
                    return $this->redirect(['site/view', 'id' => $model->id]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'tags' => $tagService::getList(),
            'selectedTags' => $selectedTags, 
            'selectedCategory' => $selectedCategory,
            'categories' => Category::getList(),
        ]);
    }
    public function actionDelete($id)
    {
        Article::findOne($id)->delete();

        return $this->redirect(['/profile', 'id'=>Yii::$app->user->identity->id ?? '0']);
    }
    public function actionSetImage($id)
    {
        $model = new ImageUpload;

        if($this->request->isPost)
        {
            $article = Article::findOne($id);
            $file = UploadedFile::getInstance($model, 'image');
        
            if($article->saveImage($model->uploadFile($file)))
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
    
    public function actionLike($id){
        $user = Yii::$app->user->identity;
        $article = Article::findOne($id);
        $this->evaluationService->like($article, $user);
        return $this->redirect(['site/view', 'id'=>$id]);
    }
    public function actionUnlike($id){
        $user = Yii::$app->user->identity;
        $article = Article::findOne($id);
        $this->evaluationService->unlike($article, $user);
        return $this->redirect(['site/view', 'id'=>$id]);
    }
}
