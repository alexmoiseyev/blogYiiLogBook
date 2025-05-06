<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m250505_103852_create_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'title'=>$this->string(),
            'text'=>$this->string(),
            'checked'=>$this->integer()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%report}}');
    }
}
