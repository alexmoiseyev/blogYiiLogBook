<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $title
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }
    public static function getAll()
    {
        return Category::find()->all();
    }
    public function getArticles(){
        return $this->hasMany(Article::class, ['category_id'=>'id']);
    }
    public function getArticlesCount()
    {
        return $this->getArticles()->count();
    }
    public static function getArticlesByCategory($id)
    {
        $data = Article::find()
        ->where(['category_id'=>$id])
        ->all();
        
        return $data;
    }
    public static function getList(): array
    {
        return ArrayHelper::map(self::find()->select(['id', 'title'])->all(), 'id', 'title');
    }
}
