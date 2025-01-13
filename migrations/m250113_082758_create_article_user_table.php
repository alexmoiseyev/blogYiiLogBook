<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_user}}`.
 */
class m250113_082758_create_article_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article_user', [
            'id' => $this->primaryKey(),
            'article_id'=>$this->integer(),
            'user_id'=>$this->integer()
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'user_article_article_id',
            'article_user',
            'article_id'
        );


        // add foreign key for table `user`
        $this->addForeignKey(
            'user_article_article_id',
            'article_user',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx_user_id',
            'article_user',
            'user_id'
        );


        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-user_id',
            'article_user',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }
    public function down()
    {
        $this->dropTable('article_user');
    }
}
