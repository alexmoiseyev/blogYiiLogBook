<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string|null $title
 *
 * @property ArticleTag[] $articleTags
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
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

    /**
     * Gets query for [[ArticleTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id']);
    }
    
    public function getArticleTags()
    {
        return $this->hasMany(ArticleTag::class, ['tag_id' => 'id']);
    }
    public function getArticlesCount()
    {
        return $this->getArticles()->count();
    }
    public static function getArticlesByTag($id)
    {
        // build a DB query to get all articles
        $data = Article::find()
    ->innerJoin('article_tag', 'article_tag.article_id = article.id')
    ->where(['article_tag.tag_id' => $id])
    ->all();
        
        return $data;
    }
    public static function getArticlesByTagQuery($id)
    {
        return Article::find()
            ->innerJoinWith('tags')
            ->where(['tag.id' => $id]);
    }
}
