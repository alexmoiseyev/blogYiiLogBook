<?php

namespace app\controllers;
use app\models\Article;
use app\models\ImageUpload;
use app\models\Tag;
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
}
